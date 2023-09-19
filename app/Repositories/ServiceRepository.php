<?php

namespace App\Repositories;

use App\Exceptions\EntityNotFoundException;
use App\Models\Service;

class ServiceRepository extends BaseRepository
{
    public function __construct(Service $service)
    {
        $this->model = $service;
    }

    /**
     * 以字串取得服務
     *
     * @param string $service 服務名稱
     * @return \App\Models\Service
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function getServiceByString(string $service): Service
    {
        $service = $this->model
            ->where('services.name', $service)
            ->first();

        if (is_null($service)) {
            throw new EntityNotFoundException('找不到該服務');
        }

        return $service;
    }
}
