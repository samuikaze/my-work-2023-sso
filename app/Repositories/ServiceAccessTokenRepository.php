<?php

namespace App\Repositories;

use App\Exceptions\EntityNotFoundException;
use App\Models\ServiceAccessToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ServiceAccessTokenRepository extends BaseRepository
{
    public function __construct(ServiceAccessToken $service_access_token)
    {
        $this->model = $service_access_token;
    }

    /**
     * 依據 UUID 取得服務跳轉權杖
     *
     * @param string $uuid 權杖唯一識別碼
     * @return \App\Models\ServiceAccessToken
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function getTokenByUuid(string $uuid): ServiceAccessToken
    {
        $token = $this->model
            ->where('service_access_tokens.token', $uuid)
            ->where('service_access_tokens.expired_at', '>=', Carbon::now())
            ->first();

        if (is_null($token)) {
            throw new EntityNotFoundException('驗證失敗');
        }

        return $token;
    }

    /**
     * 移除過期服務跳轉權杖
     *
     * @return int
     */
    public function removeExpiredToken(): int
    {
        if ($this->auto_commit) {
            DB::beginTransaction();
        }

        try {
            $effected = $this->model
                ->where('service_access_tokens.expired_at', '<', Carbon::now())
                ->delete();

            if ($this->auto_commit) {
                DB::commit();
            }
        } catch (Exception $e) {
            report($e);

            if ($this->auto_commit) {
                DB::rollBack();
            }

            throw $e;
        }

        return $effected;
    }
}
