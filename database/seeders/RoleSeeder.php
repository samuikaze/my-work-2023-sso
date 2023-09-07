<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
                'id' => 1,
                'name' => 'Administrator',
                'description' => '超級管理員',
            ],
            [
                'id' => 2,
                'name' => 'User',
                'description' => '一般使用者',
            ],
        ];

        foreach ($data as $role) {
            Role::create($role);
        }
    }
}
