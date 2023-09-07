<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
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
                'name' => 'LSGamesFrontend - Frontstage',
                'status' => 1,
            ],
            [
                'id' => 2,
                'name' => 'LSGamesFrontend - Backstage',
                'status' => 1,
            ],
        ];

        foreach ($data as $service) {
            Service::create($service);
        }
    }
}
