<?php

namespace App\Virtual\Requests;

/**
 * 重設密碼請求 DTO
 *
 * @OA\Schema(
 *   title="重設密碼請求酬載",
 *   description="重設密碼時需傳入的資料",
 *   type="object",
 *   required={"email", "token", "password", "password_confirmation"}
 * )
 */
class ResetPasswordRequest
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
     * 權杖
     *
     * @var string
     *
     * @OA\Property(
     *   description="重設密碼的權杖",
     *   example="eyJpdiI6IkJPVE1rMlhtT01xSEx2WER4a0srYnc9..."
     * )
     */
    public $token;

    /**
     * 新密碼
     *
     * @var string
     *
     * @OA\Property(
     *   description="新密碼",
     *   example="pAs$w0Rd2"
     * )
     */
    public $password;

    /**
     * 確認新密碼
     *
     * @var string
     *
     * @OA\Property(
     *   description="確認新密碼",
     *   example="pAs$w0Rd2"
     * )
     */
    public $password_confirmation;
}
