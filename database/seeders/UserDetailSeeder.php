<?php

namespace Database\Seeders;

use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class UserDetailSeeder extends Seeder
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
                'user_id' => 1,
                'username' => '超級管理員',
            ],
        ];

        foreach ($data as $role) {
            UserDetail::create($role);
        }
    }
}
