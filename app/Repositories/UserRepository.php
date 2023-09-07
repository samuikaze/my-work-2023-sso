<?php

namespace App\Repositories;

use App\Exceptions\EntityNotFoundException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * 以電子郵件地址或帳號找出資料
     *
     * @param string $account 帳號
     * @param string $email 電子郵件地址
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Contracts\Queue\EntityNotFoundException
     */
    public function findUserByAccountOrEmail(string $account, string $email): Model
    {
        $user = $this->model
            ->where('account', $account)
            ->orWhere('email', $email)
            ->first();

        if (is_null($user)) {
            throw new EntityNotFoundException('找不到該使用者');
        }

        return $user;
    }

    /**
     * 以電子郵件地址與帳號找出資料
     *
     * @param string $account 帳號
     * @param string $email 電子郵件地址
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Contracts\Queue\EntityNotFoundException
     */
    public function findUserByAccountAndEmail(string $account, string $email): Model
    {
        $user = $this->model
            ->where('account', $account)
            ->where('email', $email)
            ->first();

        if (is_null($user)) {
            throw new EntityNotFoundException('找不到該使用者');
        }

        return $user;
    }

    /**
     * 以使用者帳號 PKs 取得帳號資訊
     *
     * @param array<int, int> $id 帳號 PKs
     * @return \Illuminate\Support\Collection<int, \Illuminate\Database\Eloquent\Model>
     */
    public function getUsersInformation(array $id): Collection
    {
        return $this->model
            ->join('user_details AS ud', 'ud.user_id', 'users.id')
            ->select(
                'users.id',
                'users.account',
                'ud.username'
            )
            ->whereIn('users.id', $id)
            ->get();
    }
}
