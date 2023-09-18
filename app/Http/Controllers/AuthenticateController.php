<?php

namespace App\Http\Controllers;

use App\Enums\TokenType;
use App\Services\AuthenticateService;
use App\Services\SecurityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

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
class AuthenticateController extends Controller
{
    /**
     * AuthenticateService
     *
     * @var \App\Services\AuthenticateService
     */
    protected $authenticate_service;

    /**
     * SecurityService
     *
     * @var \App\Services\SecurityService
     */
    protected $security_service;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\AuthenticateService $authenticate_service
     * @param \App\Services\SecurityService $security_service
     * @return void
     */
    public function __construct(
        AuthenticateService $authenticate_service,
        SecurityService $security_service
    ) {
        $this->authenticate_service = $authenticate_service;
        $this->security_service = $security_service;
    }

    /**
     * 註冊
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/api/v1/user/signup",
     *   summary="註冊",
     *   tags={"AccountData v1"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       ref="#/components/schemas/SignUpRequest"
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="註冊且登入成功",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             ref="#/components/schemas/SignInResponse"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="提供的資料不正確或註冊過程中發生錯誤",
     *   ),
     * )
     */
    public function signUp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'account' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed'],
            'email' => ['required', 'email'],
            'username' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                error: $validator->errors(),
                status: self::HTTP_BAD_REQUEST
            );
        }

        try {
            $this->authenticate_service->signUp(
                $request->input('account'),
                $request->input('password'),
                $request->input('email'),
                $request->input('username'),
            );
        } catch (InvalidArgumentException $e) {
            return $this->response(
                error: $e->getMessage(),
                status: self::HTTP_BAD_REQUEST
            );
        }

        $signin_request = new Request();
        $signin_request->merge([
            'account' => $request->input('account'),
            'password' => $request->input('password'),
        ]);

        $response = $this->signIn($signin_request);

        return $response;
    }

    /**
     * 登入
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/api/v1/user/signin",
     *   summary="登入",
     *   tags={"AccountData v1"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       ref="#/components/schemas/SignInRequest"
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="登入成功",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             ref="#/components/schemas/SignInResponse"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="提供的資料不正確或登入過程中發生錯誤",
     *   ),
     * )
     */
    public function signIn(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'account' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                error: '登入失敗',
                status: self::HTTP_UNAUTHORIZED
            );
        }

        try {
            [
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ] = $this->authenticate_service->userAuthentication(
                $request->input('account'),
                $request->input('password')
            );
        } catch (InvalidArgumentException $e) {
            return $this->response(
                error: $e->getMessage(),
                status: self::HTTP_UNAUTHORIZED
            );
        }

        $response = [
            'accessToken' => [
                'type' => 'Bearer',
                'token' => $access_token,
            ],
            'refreshToken' => [
                'type' => 'Bearer',
                'token' => $refresh_token,
            ],
        ];

        return $this->response(data: $response);
    }

    /**
     * 登出
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/api/v1/user/signout",
     *   summary="登出",
     *   tags={"AccountData v1"},
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *     response="200",
     *     description="登出成功",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             type="object"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="401",
     *     description="驗證失敗"
     *   )
     * )
     */
    public function signOut(Request $request): JsonResponse
    {
        $access_token = $request->bearerToken();
        if (is_null($access_token)) {
            return $this->response();
        }

        $this->authenticate_service->signOut($access_token);

        return $this->response();
    }

    /**
     * 取得新權杖
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/api/v1/user/token/refresh",
     *   summary="取得新權杖",
     *   tags={"Authentication v1"},
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *     response="200",
     *     description="成功取得新權杖",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             ref="#/components/schemas/SignInResponse"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="401",
     *     description="驗證失敗"
     *   )
     * )
     */
    public function refreshToken(Request $request)
    {
        $refresh_token = $request->bearerToken();
        if (is_null($refresh_token)) {
            return $this->response('缺少必要的資訊', null, self::HTTP_BAD_REQUEST);
        }

        try {
            [
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ] = $this->authenticate_service->getNewToken($refresh_token);
        } catch (InvalidArgumentException $e) {
            return $this->response(
                error: $e->getMessage(),
                status: self::HTTP_UNAUTHORIZED
            );
        }

        $response = [
            'accessToken' => [
                'type' => 'Bearer',
                'token' => $access_token,
            ],
            'refreshToken' => [
                'type' => 'Bearer',
                'token' => $refresh_token,
            ],
        ];

        return $this->response(data: $response);
    }

    /**
     * 忘記密碼
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/api/v1/forget/password",
     *   summary="忘記密碼",
     *   tags={"AccountData v1"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       ref="#/components/schemas/ForgetPasswordRequest"
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="請求重設密碼成功",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             type="object"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="輸入的電子郵件信箱不正確或處理過程中發現資料錯誤"
     *   ),
     * )
     */
    public function forgetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'account' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                error: '輸入的電子郵件信箱或帳號不正確',
                status: self::HTTP_BAD_REQUEST
            );
        }

        try {
            $token = $this->security_service->forgetPassword(
                $request->input('email'),
                $request->input('account')
            );
        } catch (InvalidArgumentException $e) {
            return $this->response(
                error: $e->getMessage(),
                status: self::HTTP_BAD_REQUEST
            );
        }

        if (! is_null($token)) {
            return $this->response(null, [
                'message' => '由於沒有郵件伺服器，暫時只能以此方式展示',
                'token' => $token,
            ]);
        }

        return $this->response();
    }

    /**
     * 重設密碼
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   path="/api/v1/reset/password",
     *   summary="重設密碼",
     *   tags={"AccountData v1"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       ref="#/components/schemas/ResetPasswordRequest"
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="密碼重設成功",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             type="object"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="輸入的資料不正確或處理過程中發現資料錯誤"
     *   ),
     * )
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                error: '重設密碼失敗，請再試一次',
                status: self::HTTP_BAD_REQUEST
            );
        }

        try {
            $this->security_service->resetPassword(
                $request->input('email'),
                $request->input('token'),
                $request->input('password')
            );
        } catch (InvalidArgumentException $e) {
            return $this->response(
                error: $e->getMessage(),
                status: self::HTTP_BAD_REQUEST
            );
        }

        return $this->response();
    }

    /**
     * 驗證登入狀態，並取得帳號資訊
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   path="/api/v1/user",
     *   summary="驗證登入狀態，並取得帳號資訊",
     *   tags={"Authentication v1"},
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *     response="200",
     *     description="驗證成功",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             ref="#/components/schemas/AuthorizeUserResponse"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="401",
     *     description="驗證失敗"
     *   ),
     * )
     */
    public function authorization(Request $request): JsonResponse
    {
        $access_token = $request->bearerToken();

        if (is_null($access_token)) {
            return $this->response(
                error: '驗證失敗',
                status: self::HTTP_UNAUTHORIZED
            );
        }

        try {
            $user = $this->authenticate_service->verifyJWToken($access_token, true, TokenType::ACCESS_TOKEN);
        } catch (InvalidArgumentException $e) {
            return $this->response(
                error: $e->getMessage(),
                status: self::HTTP_UNAUTHORIZED
            );
        }

        return $this->response(data: $user);
    }

    /**
     * 取得重設密碼權杖資訊
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   path="/api/v1/reset/password/token",
     *   summary="取得重設密碼權杖資訊",
     *   tags={"Authentication v1"},
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *     response="200",
     *     description="驗證成功",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             ref="#/components/schemas/ResetPasswordTokenInformationResponse"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="401",
     *     description="驗證失敗"
     *   ),
     * )
     */
    public function getResetPasswordInformation(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (is_null($token)) {
            return $this->response(
                '請確實給出權杖',
                null,
                self::HTTP_UNAUTHORIZED
            );
        }

        try {
            $info = $this->security_service->getResetPasswordInformation($token);
        } catch (InvalidArgumentException $e) {
            return $this->response(
                $e->getMessage(),
                null,
                self::HTTP_UNAUTHORIZED
            );
        }

        return $this->response(null, $info);
    }

    /**
     * 更新使用者資料
     *
     * @OA\Patch(
     *   path="/api/v1/user",
     *   summary="更新使用者資料",
     *   tags={"Authentication v1"},
     *   security={{ "apiAuth": {} }},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       ref="#/components/schemas/UpdateUserDataRequest"
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="更新成功",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             ref="#/components/schemas/UpdateUserDataResponse"
     *           )
     *         )
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response="401",
     *     description="驗證失敗"
     *   ),
     * )
     */
    public function updateUserData(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['nullable', 'string', 'confirmed'],
            'email' => ['required', 'string', 'email'],
            'virtualAvator' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                error: '給定的資料格式不正確',
                status: self::HTTP_BAD_REQUEST
            );
        }

        $new_user = $this->security_service->updateUserData(
            $request->input('authorization.id'),
            $request->input('username'),
            $request->input('email'),
            $request->input('virtualAvator'),
            $request->input('password')
        );

        return $this->response(data: $new_user);
    }
}
