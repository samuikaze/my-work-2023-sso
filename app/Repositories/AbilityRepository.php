<?php

namespace App\Repositories;

use App\Models\Ability;
use Illuminate\Support\Collection;

class AbilityRepository extends BaseRepository
{
    public function __construct(Ability $ability)
    {
        $this->model = $ability;
    }

    /**
     * 以角色 PKs 取得權限資料
     *
     * @param array<int, int>|\Illuminate\Support\Collection<int, int> $ids 角色 PKs
     * @return \Illuminate\Support\Collection<int, \App\Models\Ability>
     */
    public function getAbilitiesByRoleIds(array|Collection $ids): Collection
    {
        return $this->model
            ->join('role_abilities AS ra', 'ra.ability_id', 'abilities.id')
            ->whereIn('ra.role_id', $ids)
            ->select('abilities.*')
            ->get();
    }

    /**
     * 取得角色 PK 擁有的所有權限 ID
     *
     * @param array<int, int>|\Illuminate\Support\Collection<int, int> $role_ids 角色 PK
     * @return \Illuminate\Support\Collection<int, int>
     */
    public function getAbilityIdsByRoleIds(array|Collection $role_ids): Collection
    {
        /** @var \Illuminate\Support\Collection<int, int> */
        $abilities = $this->model
            ->join('role_abilities AS ra', 'ra.ability_id', 'abilities.id')
            ->whereIn('ra.role_id', $role_ids)
            ->get()
            ->pluck('ability_id');

        $abilities = $abilities->unique();

        return $this->model
            ->whereIn('abilities.id', $abilities)
            ->orWhereIn('abilities.parent_ability', $abilities)
            ->get();
    }
}
