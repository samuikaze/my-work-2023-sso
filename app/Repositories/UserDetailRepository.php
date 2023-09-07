<?php

namespace App\Repositories;

use App\Models\UserDetail;

class UserDetailRepository extends BaseRepository
{
    public function __construct(UserDetail $user_detail)
    {
        $this->model = $user_detail;
    }

    /**
     * 以使用者帳號 PK 取得使用者帳號詳細資料
     *
     * @param int $user_id 使用者帳號 PK
     * @return \App\Models\UserDetail|null
     */
    public function getUserDetailByUserId(int $user_id): UserDetail | null
    {
        return $this->model
            ->where('user_id', $user_id)
            ->first();
    }
}
