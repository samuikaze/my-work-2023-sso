<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Support\Collection;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    /**
     * 以使用者帳號 PK 取得角色
     *
     * @param int $user_id 使用者帳號 PK
     * @return \Illuminate\Support\Collection<int, \App\Models\Role>
     */
    public function getRoleByUserId(int $user_id): Collection
    {
        return $this->model
            ->join('user_roles AS ur', 'ur.role_id', 'roles.id')
            ->where('ur.user_id', $user_id)
            ->select('roles.*')
            ->get();
    }
}
