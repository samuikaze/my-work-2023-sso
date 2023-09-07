<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class InformationService
{
    /**
     * UserRepository
     *
     * @var \App\Repositories\UserRepository
     */
    protected $user_repository;

    /**
     * 建構方法
     *
     * @param \App\Repositories\UserRepository $user_repository
     * @return void
     */
    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * 以使用者帳號 PKs 取得帳號資訊
     *
     * @param array<int, int> $id 使用者帳號 PKs
     * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model>
     */
    public function getUsersInformation(array $id): Collection
    {
        return $this->user_repository->getUsersInformation($id);
    }
}
