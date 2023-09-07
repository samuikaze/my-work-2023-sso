<?php

namespace App\Virtual\Requests;

/**
 * 以帳號 IDs 取得帳號資訊的請求酬載
 *
 * @OA\Schema(
 *   title="以帳號 IDs 取得帳號資訊的請求酬載",
 *   description="以帳號 IDs 取得帳號資訊的請求酬載",
 *   type="object",
 *   required={"id"}
 * )
 */
class UsersInformationRequest
{
    /**
     * 帳號 IDs
     *
     * @OA\Property(
     *   description="帳號",
     *   type="array",
     *   @OA\Items(type="integer")
     * )
     *
     * @var array
     */
    public $id;
}
