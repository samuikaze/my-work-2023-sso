<?php

namespace App\Virtual\Responses;

/**
 * 取得重置密碼權杖資訊回應
 *
 * @OA\Schema(
 *   title="取得重置密碼權杖資訊回應酬載",
 *   description="取得重置密碼權杖資訊回應酬載",
 *   type="object"
 * )
 */
class ResetPasswordTokenInformationResponse
{
    /**
     * 帳號 ID
     *
     * @var int
     *
     * @OA\Property(
     *   description="帳號 ID",
     *   type="integer",
     *   example="1"
     * )
     */
    public $id;

    /**
     * 電子郵件地址
     *
     * @var string
     *
     * @OA\Property(
     *   description="電子郵件地址",
     *   type="string",
     *   example="test@example.com"
     * )
     */
    public $email;
}
