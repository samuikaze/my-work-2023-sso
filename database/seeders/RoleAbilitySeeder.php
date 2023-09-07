<?php

namespace Database\Seeders;

use App\Models\RoleAbility;
use Illuminate\Database\Seeder;

class RoleAbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'role_id' => 1,
                'abilities' => [1, 2, 3, 4, 8, 13],
            ],
            [
                'role_id' => 2,
                'abilities' => [1, 2, 3, 4, 8],
            ],
        ];

        foreach ($data as $set) {
            $role = $set['role_id'];
            foreach ($set['abilities'] as $ability) {
                RoleAbility::create([
                    'role_id' => $role,
                    'ability_id' => $ability,
                ]);
            }
        }
    }
}
