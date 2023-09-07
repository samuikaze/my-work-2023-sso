<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'account' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('123'),
                'status' => 1,
            ],
        ];

        foreach ($data as $role) {
            User::create($role);
        }
    }
}
