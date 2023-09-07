<?php

namespace App\Virtual\Commons;

/**
 * 權限格式
 *
 * @OA\Schema(
 *   title="權限格式",
 *   description="權限格式",
 *   type="object"
 * )
 */
class Ability
{
    /**
     * 權限 PK
     *
     * @var int
     *
     * @OA\Property(
     *   description="權限 PK",
     *   example=1
     * )
     */
    public $id;

    /**
     * 權限名稱
     *
     * @var string
     *
     * @OA\Property(
     *   description="權限名稱",
     *   example="News"
     * )
     */
    public $name;

    /**
     * 權限描述
     *
     * @var string
     *
     * @OA\Property(
     *   description="權限描述",
     *   example="最新消息"
     * )
     */
    public $description;
}
