<?php

namespace App\Repositories;

use App\Exceptions\EntityNotFoundException;
use App\Models\ForgetPasswordRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class ForgetPasswordRequestRepository extends BaseRepository
{
    public function __construct(ForgetPasswordRequest $forget_password_request)
    {
        $this->model = $forget_password_request;
    }

    /**
     * 以電子郵件地址、權杖找出重置密碼的申請
     *
     * @param string $email 電子郵件地址
     * @param string $token 權杖
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function findApplyByEmailAndToken(string $email, string $token): ForgetPasswordRequest
    {
        $apply = $this->model
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (is_null($apply)) {
            throw new EntityNotFoundException('找不到該使用者資料');
        }

        return $apply;
    }

    public function deleteTokensByEmail(string $email): bool
    {
        if ($this->auto_commit) {
            DB::beginTransaction();
        }

        try {
            $effected = $this->model
                ->where('forget_password_requests.email', $email)
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
