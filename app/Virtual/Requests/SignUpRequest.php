<?php

namespace App\Virtual\Requests;

/**
 * 註冊請求酬載
 *
 * @OA\Schema(
 *   title="註冊請求酬載",
 *   description="註冊帳號時需傳入的資料",
 *   type="object",
 *   required={"account", "password", "password_confirmation", "email", "name"}
 * )
 */
class SignUpRequest
{
    /**
     * 帳號
     *
     * @var string
     *
     * @OA\Property(
     *   description="帳號",
     *   example="user-1"
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

    /**
     * 確認密碼
     *
     * @var string
     *
     * @OA\Property(
     *   description="確認密碼",
     *   example="pAs$w0Rd"
     * )
     */
    public $password_confirmation;

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
     * 暱稱
     *
     * @var string
     *
     * @OA\Property(
     *   description="暱稱",
     *   example="user"
     * )
     */
    public $username;
}
