<?php

namespace App\Virtual\Requests;

/**
 * 驗證登入狀態並取得帳號資訊的回應酬載
 *
 * @OA\Schema(
 *   title="驗證登入狀態並取得帳號資訊的回應酬載",
 *   description="驗證登入狀態並取得帳號資訊的回應酬載",
 *   type="object",
 *   required={"username", "email"}
 * )
 */
class UpdateUserDataRequest
{
    /**
     * 暱稱
     *
     * @var string
     *
     * @OA\Property(
     *   description="暱稱",
     *   example="一般使用者"
     * )
     */
    public $username;

    /**
     * 電子郵件信箱
     *
     * @var string
     *
     * @OA\Property(
     *   description="電子郵件信箱",
     *   example="test@example.com"
     * )
     */
    public $email;

    /**
     * 密碼
     *
     * @var string
     *
     * @OA\Property(
     *   description="密碼",
     *   example="password"
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
     *   example="password"
     * )
     */
    public $password_confirmation;
}
