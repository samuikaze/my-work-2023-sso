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
}
