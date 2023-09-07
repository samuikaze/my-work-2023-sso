<?php

namespace App\Services;

use App\Repositories\ActionLogRepository;

class ActionLogService
{
    /**
     * LoggingRepository
     *
     * @var \App\Repositories\ActionLogRepository
     */
    protected $action_log_repository;

    /**
     * 建構方法
     *
     * @param \App\Repositories\ActionLogRepository $action_log_repository
     * @return void
     */
    public function __construct(ActionLogRepository $action_log_repository)
    {
        $this->action_log_repository = $action_log_repository;
    }

    /**
     * 寫入日誌
     *
     * @param string $uri 請求 URI
     * @param string $method 存取方法
     * @param int $status HTTP 狀態
     * @param string|null $request_payloads 請求酬載
     * @param int|null $user_id 使用者帳號 PK
     * @param int|null $service_id 服務 PK
     * @param string|null $ip 來源 IP
     * @return void
     */
    public function loggingActions(
        string $uri,
        string $method,
        int $status,
        string $request_payloads = null,
        int $user_id = null,
        int $service_id = null,
        string $ip = null
    ): void {
        $this->action_log_repository->create([
            'uri' => $uri,
            'method' => $method,
            'user_id' => $user_id,
            'service_id' => $service_id,
            'access_ip' => $ip,
            'code' => $status,
            'request_payloads' => $request_payloads,
        ]);
    }
}
