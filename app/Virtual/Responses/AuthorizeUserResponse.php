<?php

namespace App\Virtual\Responses;

/**
 * 驗證登入狀態並取得帳號資訊的回應酬載
 *
 * @OA\Schema(
 *   title="驗證登入狀態並取得帳號資訊的回應酬載",
 *   description="驗證登入狀態並取得帳號資訊的回應酬載",
 *   type="object"
 * )
 */
class AuthorizeUserResponse
{
    /**
     * 使用者帳號 PK
     *
     * @var int
     *
     * @OA\Property(
     *   description="使用者帳號 PK",
     *   example="1"
     * )
     */
    public $id;

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
     * 連絡電話
     *
     * @var string
     *
     * @OA\Property(
     *   description="連絡電話",
     *   example="0987654321"
     * )
     */
    public $phone;

    /**
     * 角色
     *
     * @var array
     *
     * @OA\Property(
     *   description="角色",
     *   type="array",
     *   @OA\Items(ref="#/components/schemas/Role")
     * )
     */
    public $roles;

    /**
     * 權限
     *
     * @var array
     *
     * @OA\Property(
     *   description="權限",
     *   type="array",
     *   @OA\Items(ref="#/components/schemas/Ability")
     * )
     */
    public $abilities;

    /**
     * 電子郵件驗證時間
     *
     * @var string
     *
     * @OA\Property(
     *   description="電子郵件驗證時間",
     *   example="2023-09-07T00:00:00.000Z"
     * )
     */
    public $emailVerifiedAt;

    /**
     * 帳號註冊時間
     *
     * @var string
     *
     * @OA\Property(
     *   description="帳號註冊時間",
     *   example="2023-09-07T00:00:00.000Z"
     * )
     */
    public $registeredAt;

    /**
     * 最後更新時間
     *
     * @var string
     *
     * @OA\Property(
     *   description="最後更新時間",
     *   example="2023-09-07T00:00:00.000Z"
     * )
     */
    public $updatedAt;
}
