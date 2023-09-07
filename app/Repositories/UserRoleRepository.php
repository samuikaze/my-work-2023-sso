<?php

namespace App\Repositories;

use App\Models\UserRole;
use Illuminate\Support\Collection;

class UserRoleRepository extends BaseRepository
{
    public function __construct(UserRole $user_role)
    {
        $this->model = $user_role;
    }

    /**
     * 以帳號 ID 取得角色
     *
     * @param int $user_id 帳號 ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesByUserId(int $user_id): Collection
    {
        return $this->model
            ->select(
                'roles.id',
                'roles.name',
                'roles.description',
            )
            ->join('users', 'user_roles.user_id', 'users.id')
            ->join('roles', 'user_roles.role_id', 'roles.id')
            ->where('users.id', $user_id)
            ->get();
    }
}
