<?php

namespace App\Http\Controllers;

use App\Services\InformationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 驗證 Controller
 *
 * @OA\Tag(
 *   name="AccountData v1",
 *   description="範例相關"
 * ),
 * @OA\Tag(
 *   name="Authentication v1",
 *   description="驗證帳號相關"
 * )
 */
class InformationController extends Controller
{
    /**
     * InformationService
     *
     * @var \App\Services\InformationService
     */
    protected $information_service;

    /**
     * 建構方法
     *
     * @param \App\Services\InformationService $information_service
     * @return void
     */
    public function __construct(InformationService $information_service)
    {
        $this->information_service = $information_service;
    }

    /**
     * 以使用者帳號 PKs 取得帳號資訊
     *
     * @OA\Post(
     *   path="/api/v1/users",
     *   summary="以使用者帳號 PKs 取得帳號資訊",
     *   tags={"AccountInformations v1"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       ref="#/components/schemas/UsersInformationRequest"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="成功以使用者帳號 PKs 取得帳號資訊",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UsersInformationResponse")
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="提供的資料不正確或註冊過程中發生錯誤",
     *   ),
     *   @OA\Response(
     *     response=500,
     *     description="系統發生無法預期的錯誤"
     *   )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersInformation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'array'],
            'id.*' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return $this->response(
                error: '未指定使用者帳號 ID 或格式錯誤',
                status: self::HTTP_BAD_REQUEST
            );
        }

        $informations = $this->information_service->getUsersInformation(
            $request->input('id')
        );

        return $this->response(data: $informations);
    }
}
