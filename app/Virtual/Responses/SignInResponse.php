<?php

namespace App\Virtual\Responses;

/**
 * 登入回應
 *
 * @OA\Schema(
 *   title="登入回應",
 *   description="登入成功回傳的酬載",
 *   type="object"
 * )
 */
class SignInResponse
{
    /**
     * 存取權杖
     *
     * @var string
     *
     * @OA\Property(
     *   description="存取權杖",
     *   ref="#/components/schemas/Token"
     * )
     */
    public $accessToken;

    /**
     * 重整權杖
     *
     * @var string
     *
     * @OA\Property(
     *   description="重整權杖",
     *   ref="#/components/schemas/Token"
     * )
     */
    public $refreshToken;
}
