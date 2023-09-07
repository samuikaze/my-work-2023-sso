<?php

namespace App\Virtual\Commons;

/**
 * 權杖格式
 *
 * @OA\Schema(
 *   title="權杖格式",
 *   description="權杖格式",
 *   type="object"
 * )
 */
class Token
{
    /**
     * 權杖種類
     *
     * @var string
     *
     * @OA\Property(
     *   description="權杖種類",
     *   example="Bearer"
     * )
     */
    public $tokenType;

    /**
     * 存取權杖
     *
     * @var string
     *
     * @OA\Property(
     *   description="存取權杖",
     *   example="WVZWS1ZVNHdiRkJpTTJNNVVGTkpjMGx1V21oa..."
     * )
     */
    public $token;
}
