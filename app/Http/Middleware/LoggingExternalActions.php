<?php

namespace App\Http\Middleware;

use App\Services\ActionLogService;
use App\Services\AuthenticateService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use InvalidArgumentException;

class LoggingExternalActions
{
    /**
     * 來源名稱
     *
     * @var int
     */
    protected const SOURCE = 1;

    /**
     * ActionLogService
     *
     * @var \App\Services\ActionLogService
     */
    protected $action_log_service;

    /**
     * AuthenticateService
     *
     * @var \App\Services\AuthenticateService
     */
    protected $authenticate_service;

    /**
     * 建構方法
     *
     * @param \App\Services\ActionLogService $action_log_service
     * @param \App\Services\AuthenticateService $authenticate_service
     * @return void
     */
    public function __construct(
        ActionLogService $action_log_service,
        AuthenticateService $authenticate_service
    ) {
        $this->action_log_service = $action_log_service;
        $this->authenticate_service = $authenticate_service;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var \Illuminate\Http\JsonResponse $response */
        try {
            $response = $next($request);

            if (! is_null($response)) {
                [
                    'status' => $status,
                ] = $response->getOriginalContent();
            } else {
                $status = 0;
            }

            $method = $request->method();
            $ip = $request->ip();
            $uri = $request->path();
            $request_payloads = $request->all();
            if (count($request_payloads) === 0) {
                $request_payloads = null;
            } else {
                $request_payloads = json_encode($request_payloads, JSON_UNESCAPED_UNICODE);
            }

            $user_id = $this->getUserId($request);

            $this->action_log_service->loggingActions($uri, $method, $status, $request_payloads, $user_id, self::SOURCE, $ip);
        } catch (Exception $e) {
            report($e);

            if (! is_null($e->getPrevious())) {
                report($e->getPrevious());
            }

            throw $e;
        }

        return $response;
    }

    /**
     * 從存取權杖取得使用者帳號 PK
     *
     * @param \Illuminate\Http\Request $request
     * @return int|null
     */
    protected function getUserId(Request $request): int|null
    {
        $access_token = $request->bearerToken();
        if (is_null($access_token)) {
            return null;
        }

        try {
            [
                'userId' => $user_id,
            ] = $this->authenticate_service->decodeJWTToken($access_token);

            return $user_id;
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }
}
