<?php

namespace App\Virtual\Commons;

/**
 * 角色格式
 *
 * @OA\Schema(
 *   title="角色格式",
 *   description="角色格式",
 *   type="object"
 * )
 */
class Role
{
    /**
     * 角色 PK
     *
     * @var int
     *
     * @OA\Property(
     *   description="角色 PK",
     *   example=1
     * )
     */
    public $id;

    /**
     * 角色名稱
     *
     * @var string
     *
     * @OA\Property(
     *   description="角色名稱",
     *   example="User"
     * )
     */
    public $name;

    /**
     * 角色描述
     *
     * @var string
     *
     * @OA\Property(
     *   description="角色描述",
     *   example="一般使用者"
     * )
     */
    public $description;
}
