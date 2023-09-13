<?php

namespace App\Virtual\Responses;

/**
 * @OA\Schema(
 *   title="以多筆 PK 取得使用者帳號資訊單筆資料結構",
 *   description="以多筆 PK 取得使用者帳號資訊單筆資料結構",
 *   type="object"
 * )
 */
class UsersInformationResponse
{
    /**
     * @OA\Property(
     *   description="使用者帳號 PK",
     *   example=1
     * )
     *
     * @var int
     */
    public $id;

    /**
     * @OA\Property(
     *   description="帳號",
     *   example="account"
     * )
     */
    public $account;

    /**
     * @OA\Property(
     *   description="使用者名稱",
     *   example="user"
     * )
     *
     * @var string
     */
    public $username;
}
