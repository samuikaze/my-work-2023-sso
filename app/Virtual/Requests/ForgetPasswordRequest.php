<?php

namespace App\Virtual\Requests;

/**
 * 忘記密碼請求 DTO
 *
 * @OA\Schema(
 *   title="忘記密碼請求酬載",
 *   description="忘記密碼時需傳入的資料",
 *   type="object",
 *   required={"email"}
 * )
 */
class ForgetPasswordRequest
{
    /**
     * 電子郵件地址
     *
     * @var string
     *
     * @OA\Property(
     *   description="電子郵件地址",
     *   example="test@example.com"
     * )
     */
    public $email;

    /**
     * 使用者帳號
     *
     * @var string
     *
     * @OA\Property(
     *   description="使用者帳號",
     *   example="user-1"
     * )
     */
    public $account;
}
