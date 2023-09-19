<?php

namespace App\Virtual\Requests;

/**
 * 產生系統跳轉用存取權杖請求酬載
 *
 * @OA\Schema(
 *   title="產生系統跳轉用存取權杖請求酬載",
 *   description="產生系統跳轉用存取權杖請求酬載",
 *   type="object",
 *   required={"system", "accessToken", "refreshToken"}
 * )
 */
class GenerateSystemAccessTokenRequest
{
    /**
     * 跳轉目標系統
     *
     * @var string
     *
     * @OA\Property(
     *   description="跳轉目標系統",
     *   example="frontend"
     * )
     */
    public $system;

    /**
     * 使用者存取權杖
     *
     * @var string
     *
     * @OA\Property(
     *   description="使用者存取權杖",
     *   example="WVZWS1ZVNHdiRkJpTTJNNVVGTkpjMGx1V21oa..."
     * )
     */
    public $accessToken;

    /**
     * 使用者重整權杖
     *
     * @var string
     *
     * @OA\Property(
     *   description="使用者重整權杖",
     *   example="WVZWS1ZVNHdiRkJpTTJNNVVGTkpjMGx1V21oa..."
     * )
     */
    public $refreshToken;
}
