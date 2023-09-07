<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->callWith([
            RoleSeeder::class,
            AbilitySeeder::class,
            RoleAbilitySeeder::class,
            UserSeeder::class,
            UserDetailSeeder::class,
            UserRoleSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
