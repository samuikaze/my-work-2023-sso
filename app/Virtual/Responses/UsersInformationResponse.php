<?php

namespace App\Virtual\Responses;

/**
 * @OA\Schema(
 *   title="以多筆 ID 取得帳號資訊單筆資料結構",
 *   description="以多筆 ID 取得帳號資訊單筆資料結構",
 *   type="object"
 * )
 */
class UsersInformationResponse
{
    /**
     * @OA\Property(
     *   description="帳號 ID",
     *   example=1
     * )
     *
     * @var int
     */
    public $id;

    /**
     * @OA\Property(
     *   description="帳號名稱",
     *   example="user"
     * )
     *
     * @var string
     */
    public $name;
}
