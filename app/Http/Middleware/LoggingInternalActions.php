<?php

namespace App\Http\Middleware;

use App\Services\ActionLogService;
use App\Services\AuthenticateService;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use InvalidArgumentException;

class LoggingInternalActions
{
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
        $response = $next($request);

        $status = $response->getStatusCode();
        $uri = $request->path();
        $method = $request->method();
        $ip = $request->ip();
        $source = $request->input('systems') ?? null;

        $request_payloads = ['system' => ($source ?? 'unknown')];
        $request_payloads = json_encode($request_payloads, JSON_UNESCAPED_UNICODE);

        $user_id = $this->getUserId($request);

        $this->action_log_service->loggingActions($uri, $method, $status, $request_payloads, $user_id, $source, $ip);

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
