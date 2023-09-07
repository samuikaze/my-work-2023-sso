<?php

namespace App\Virtual\Requests;

/**
 * 登入請求酬載
 *
 * @OA\Schema(
 *   title="登入請求酬載",
 *   description="登入時需傳入的資料",
 *   type="object",
 *   required={"userAccount"}
 * )
 */
class SignInRequest
{
    /**
     * 帳號
     *
     * @var string
     *
     * @OA\Property(
     *   description="帳號",
     *   example="user_1"
     * )
     */
    public $account;

    /**
     * 密碼
     *
     * @var string
     *
     * @OA\Property(
     *   description="密碼",
     *   example="pAs$w0Rd"
     * )
     */
    public $password;
}
