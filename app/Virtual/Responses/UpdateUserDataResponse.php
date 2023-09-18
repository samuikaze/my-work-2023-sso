<?php

namespace App\Virtual\Responses;

/**
 * 更新使用者資料的回應酬載
 *
 * @OA\Schema(
 *   title="更新使用者資料的回應酬載",
 *   description="更新使用者資料的回應酬載",
 *   type="object"
 * )
 */
class UpdateUserDataResponse
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
     * 使用者帳號 PK
     *
     * @var int
     *
     * @OA\Property(
     *   description="使用者帳號 PK",
     *   example="1"
     * )
     */
    public $userId;

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
     * 虛擬形象圖檔路徑
     *
     * @var string
     *
     * @OA\Property(
     *   description="虛擬形象圖檔路徑",
     *   example="aaa/test.jpg"
     * )
     */
    public $virtualAvator;

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
}
