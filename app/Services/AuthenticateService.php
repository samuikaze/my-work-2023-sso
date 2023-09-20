<?php

namespace App\Services;

use App\Commons\Utils;
use App\Enums\Ability;
use App\Enums\TokenType;
use App\Exceptions\EntityNotFoundException;
use App\Models\User;
use App\Repositories\AbilityRepository;
use App\Repositories\RoleRepository;
use App\Repositories\ServiceAccessTokenRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\TokenRepository;
use App\Repositories\UserDetailRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserRoleRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class AuthenticateService
{   /**
     * UserRepository
     *
     * @var \App\Repositories\UserRepository
     */
    protected $user_repository;

    /**
     * UserDetailRepository
     *
     * @var \App\Repositories\UserDetailRepository
     */
    protected $user_detail_repository;

    /**
     * UserRoleRepository
     *
     * @var \App\Repositories\UserRoleRepository
     */
    protected $user_role_repository;

    /**
     * TokenRepository
     *
     * @var \App\Repositories\TokenRepository
     */
    protected $token_repository;

    /**
     * RoleRepository
     *
     * @var \App\Repositories\RoleRepository
     */
    protected $role_repository;

    /**
     * AbilityRepository
     *
     * @var \App\Repositories\AbilityRepository
     */
    protected $ability_repository;

    /**
     * ServiceRepository
     *
     * @var \App\Repositories\ServiceRepository
     */
    protected $service_repository;

    /**
     * ServiceAccessToken
     *
     * @var \App\Repositories\ServiceAccessTokenRepository
     */
    protected $service_access_token_repository;

    /**
     *
     * @param \App\Repositories\UserRepository $user_repository
     * @param \App\Repositories\UserDetailRepository $user_detail_repository
     * @param \App\Repositories\UserRoleRepository $user_role_repository
     * @param \App\Repositories\TokenRepository $token_repository
     * @param \App\Repositories\AbilityRepository $ability_repository
     * @param \App\Repositories\ServiceRepository $service_repository
     * @param \App\Repositories\ServiceAccessTokenRepository $service_access_token_repository
     * @return void
     */
    public function __construct(
        UserRepository $user_repository,
        UserDetailRepository $user_detail_repository,
        UserRoleRepository $user_role_repository,
        TokenRepository $token_repository,
        RoleRepository $role_repository,
        AbilityRepository $ability_repository,
        ServiceRepository $service_repository,
        ServiceAccessTokenRepository $service_access_token_repository
    ) {
        $this->user_repository = $user_repository;
        $this->user_detail_repository = $user_detail_repository;
        $this->user_role_repository = $user_role_repository;
        $this->token_repository = $token_repository;
        $this->role_repository = $role_repository;
        $this->ability_repository = $ability_repository;
        $this->service_repository = $service_repository;
        $this->service_access_token_repository = $service_access_token_repository;
    }

    /**
     * 註冊
     *
     * @param string $account 帳號
     * @param string $password 密碼
     * @param string $email 電子郵件地址
     * @param string $username 暱稱
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function signUp(string $account, string $password, string $email, string $username): void
    {
        $duplicate = $this->checkIfUserIsExists($account, $email);
        if ($duplicate) {
            throw new InvalidArgumentException('該帳號或電子郵件地址已被使用過');
        }

        $this->user_repository->transactionClosure(function () use ($account, $password, $email, $username) {
            $user = [
                'account' => $account,
                'password' => Hash::make($password),
                'email' => $email,
                'status' => 1
            ];

            $user = $this->user_repository->create($user);

            $user_detail = [
                'user_id' => $user->id,
                'username' => $username,
            ];

            $this->user_detail_repository->create($user_detail);

            $user_role = [
                'user_id' => $user->id,
                'role_id' => 2
            ];

            $this->user_role_repository->create($user_role);
        });
    }

    /**
     * 檢查帳號或電子郵件地址是否已經存在
     *
     * @param string $account 帳號
     * @param string $email 電子郵件地址
     * @return bool
     */
    protected function checkIfUserIsExists(string $account, string $email): bool
    {
        try {
            $this->user_repository->findUserByAccountOrEmail($account, $email);
        } catch (EntityNotFoundException $e) {
            return false;
        }

        return true;
    }

    /**
     * 登入
     *
     * @param string $account 帳號或電子郵件地址
     * @param string $password 密碼
     * @return \Illuminate\Database\Eloquent\Model[]|string[] [$user, $token, $r_token] 返回帳號資料與權杖
     *
     * @throws \InvalidArgumentException
     */
    public function userAuthentication(string $account, string $password): array
    {
        try {
            $user = $this->user_repository->findUserByAccountOrEmail($account, $account);
        } catch (EntityNotFoundException $e) {
            throw new InvalidArgumentException('帳號或密碼錯誤');
        }

        $authenticate = Hash::check($password, $user->password);
        if (! $authenticate) {
            throw new InvalidArgumentException('帳號或密碼錯誤');
        }

        return $this->generateToken($user);
    }

    /**
     * 登出
     *
     * @param string $bearer_token 存取權杖
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function signOut(string $bearer_token): void
    {
        try {
            $this->verifyJWToken($bearer_token, false, TokenType::ACCESS_TOKEN);

            [
                'userId' => $user_id,
                'jti' => $jwt_id,
            ] = $this->decodeJWTToken($bearer_token);

            $user = $this->user_repository->find($user_id);
            if (is_null($user)) {
                throw new InvalidArgumentException('無此帳號');
            }

            $access_token = $this->token_repository->getTokenByUuid(TokenType::ACCESS_TOKEN, $jwt_id);
            if (is_null($access_token)) {
                throw new InvalidArgumentException('尚未登入');
            }

            $this->token_repository->deleteTokenByUuid($jwt_id);
        } catch (InvalidArgumentException | EntityNotFoundException $e) {
            // 登出時遇到已知錯誤不拋錯
            // 因為這種時候通常是權杖過期
        } catch (Exception $e) {
            // 其它錯誤要讓它拋出去
            report($e);

            throw $e;
        }
    }

    /**
     * 取得新權杖
     *
     * @param string $refresh_token 重整權杖
     * @return array<int, string> [$user_id, $token, $r_token]
     */
    public function getNewToken(string $refresh_token)
    {
        $this->verifyJWToken($refresh_token);

        [
            'userId' => $user_id,
            'tokenType' => $token_type,
            'jti' => $jwt_id,
        ] = $this->decodeJWTToken($refresh_token);
        if ($token_type !== TokenType::REFRESH_TOKEN->value) {
            throw new InvalidArgumentException('不正確的重整權杖');
        }

        $this->token_repository->deleteTokenByUuid($jwt_id);

        $user = $this->user_repository->find($user_id);
        if (is_null($user)) {
            throw new InvalidArgumentException('無此帳號');
        }

        return $this->generateToken($user);
    }

    /**
     * 產生系統跳轉存取權杖
     *
     * @param int $user_id 使用者帳號 PK
     * @param string $system 跳轉目標系統名稱
     * @param string $access_token 存取權杖
     * @param string $refresh_token 重整權杖
     * @return array<string, string>
     */
    public function generateSystemAccessToken(int $user_id, string $system, string $access_token, string $refresh_token): array
    {
        $roles = $this->user_role_repository->getRolesByUserId($user_id)->pluck('id');
        $abilities = $this->ability_repository->getAbilityIdsByRoleIds($roles);
        $conditions = $abilities
            ->filter(function ($ability) {
                return (
                    $ability->id === Ability::BACKSTAGE->value ||
                    $ability->parent_ability === Ability::BACKSTAGE->value
                );
            })
            ->count();
        if ($conditions === 0) {
            throw new InvalidArgumentException('驗證失敗');
        }

        $this->service_repository->getServiceByString($system);

        $generate_token = Uuid::uuid4()->toString();

        $service_access_token_valid = (int) env('SERVICE_ACCESS_TOKEN_VALID_DURATION', 60);
        $service_access_token = [
            'token' => $generate_token,
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'expired_at' => Carbon::now()->addMinutes($service_access_token_valid),
        ];
        $this->service_access_token_repository->create($service_access_token);

        $token_payloads = [
            'token' => $generate_token,
            'tokenType' => TokenType::SERVICE_ACCESS_TOKEN->value,
        ];
        $token = $this->generateJWTToken(
            $token_payloads,
            (string) random_int(1000000000, 9999999999),
            $generate_token,
        );

        return [
            'token' => $token,
            'tokenType' => 'Bearer'
        ];
    }

    /**
     * 依據傳入的服務跳轉權杖取得使用者帳號的存取權杖與重整權杖
     *
     * @param string $bearer_token 服務跳轉權杖
     * @return array<string, string>
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function getSignInFromSystemAccessToken(string $bearer_token): array
    {
        [
            'token' => $uuid
        ] = $this->decodeJWTToken($bearer_token);

        $service_access_token = $this->service_access_token_repository->getTokenByUuid($uuid);

        $this->service_access_token_repository->delete($service_access_token->id);

        $this->service_access_token_repository->removeExpiredToken();

        return [
            'access_token' => $service_access_token->access_token,
            'refresh_token' => $service_access_token->refresh_token,
        ];
    }

    /**
     * 產生存取權杖與重整權杖
     *
     * @param \App\Models\User $user
     * @return array<string, string>
     */
    public function generateToken(User $user): array
    {
        // 取得角色資料
        $roles = $this->role_repository->getRoleByUserId($user->id);
        $user->roles = $roles;

        $role_abilities = $this->ability_repository->getAbilitiesByRoleIds($roles->pluck('id'));
        $user->abilities = $role_abilities;

        $user_detail = $this->user_detail_repository->getUserDetailByUserId($user->id);

        $subject = (string) random_int(1000000000, 9999999999);
        $jwt_id = Uuid::uuid4()->toString();

        $access_token_payloads = [
            'userId' => $user->id,
            'roles' => $roles->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                ];
            }),
            'scope' => $role_abilities->pluck('name'),
            'account' => $user->account,
            'username' => $user_detail->username,
            'tokenType' => TokenType::ACCESS_TOKEN->value,
        ];
        $access_token = $this->generateJWTToken($access_token_payloads, $subject, $jwt_id);

        $refresh_token_payloads = [
            'userId' => $user->id,
            'account' => $user->account,
            'username' => $user_detail->username,
            'tokenType' => TokenType::REFRESH_TOKEN->value,
        ];
        $refresh_token = $this->generateJWTToken($refresh_token_payloads, $subject, $jwt_id);

        $this->token_repository->bulkCreate([
            [
                'user_id' => $user->id,
                'token_uuid' => $jwt_id,
                'token_type' => TokenType::ACCESS_TOKEN->value,
            ], [
                'user_id' => $user->id,
                'token_uuid' => $jwt_id,
                'token_type' => TokenType::REFRESH_TOKEN->value,
            ]
        ]);

        return [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
        ];
    }

    /**
     * 檢查存取權杖有效性
     *
     * @param string|null $jwt_token 存取權杖
     * @param bool $need_data [false] 是否需要使用者帳號資訊
     * @param \App\Enums\TokenType $specific_token_type 權杖種類
     * @return bool|array<string, mixed>
     *
     * @throws \InvalidArgumentException
     */
    public function verifyJWToken(string $jwt_token = null, bool $need_data = false, TokenType $specific_token_type = null): bool|array
    {
        if (is_null($jwt_token)) {
            throw new InvalidArgumentException('驗證失敗');
        }

        [
            'userId' => $user_id,
            'account' => $user_account,
            'jti' => $jwt_id,
            'tokenType' => $token_type,
        ] = $this->decodeJWTToken($jwt_token);

        $user = $this->user_repository->find($user_id);
        if (is_null($user) || $user_account !== $user->account) {
            throw new InvalidArgumentException('驗證失敗');
        }

        $type = TokenType::tryFrom($token_type);
        if (is_null($type)) {
            throw new InvalidArgumentException('驗證失敗');
        }

        if (! is_null($specific_token_type) && $specific_token_type !== $type) {
            throw new InvalidArgumentException('驗證失敗');
        }

        $token = $this->token_repository->getTokenByUuid($type, $jwt_id);
        if (is_null($token)) {
            throw new InvalidArgumentException('驗證失敗');
        }

        if ($need_data) {
            $user_detail = $this->user_detail_repository->getUserDetailByUserId($user->id);
            $roles = $this->role_repository->getRoleByUserId($user->id)->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                ];
            });
            $abilities = $this->ability_repository->getAbilitiesByRoleIds($roles->pluck('id'))->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                ];
            });

            $response = [
                'id' => $user->id,
                'account' => $user->account,
                'email' => $user->email,
                'emailVerifiedAt' => $user->email_verified_at,
                'username' => $user_detail->username,
                'virtualAvator' => $user_detail->virtual_avator,
                'roles' => $roles,
                'abilities' => $abilities,
                'registeredAt' => $user->created_at,
                'updatedAt' => $user->updated_at,
            ];
        } else {
            $response = true;
        }

        return $response;
    }

    /**
     * 產生權杖
     *
     * @param array<string,mixed> $payloads
     * @param string $subject JWT subject
     * @param string $jwt_id JWT ID
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function generateJWTToken(array $payloads, string $subject, string $jwt_id): string
    {
        $headers = $this->generateJWTHeader();
        $payloads = $this->generateJWTPayloads($payloads, $subject, $jwt_id);
        $secret = $this->generateJWTSecret($headers, $payloads);

        $access_token = $headers.'.'.$payloads.'.'.$secret;
        $access_token = base64_encode($access_token);

        return $access_token;
    }

    /**
     * 產生 JWT 標頭
     *
     * @param bool $want_original 是否取得原始資料
     * @return array<string,string>|string
     *
     * @throws \InvalidArgumentException
     */
    protected function generateJWTHeader(bool $want_original = false)
    {
        $algorithm = env('JWT_SECRET_JWT_ALGORITHM');
        if (is_null($algorithm)) {
            throw new InvalidArgumentException('請先設定系統 JWT Secret 加密演算法');
        }

        $headers = [
            'alg' => $algorithm,
            'typ' => 'JWT',
        ];

        if ($want_original) {
            return $headers;
        }

        $headers = json_encode($headers);
        $headers = Utils::base64_url_encode($headers);

        return $headers;
    }

    /**
     * 產生 JWT 資料
     *
     * @param array $payloads
     * @param string $subject JWT subject
     * @param string $jwt_id JWT ID
     * @param bool $want_original 是否取得原始資料
     * @return array<string,string>|string
     */
    protected function generateJWTPayloads(array $payloads, string $subject, string $jwt_id, bool $want_original = false)
    {
        $now = Carbon::now();
        switch ($payloads['tokenType']) {
            case TokenType::ACCESS_TOKEN->value:
                $valid_duration = (int) env('AUTHENTICATE_VALID_DURATION', 120);
                break;
            case TokenType::REFRESH_TOKEN->value:
                $valid_duration = (int) env('REFRESH_TOKEN_VALID_DURATION', 60 * 60 * 24);
                break;
            case TokenType::SERVICE_ACCESS_TOKEN->value:
                $valid_duration = (int) env('SERVICE_ACCESS_TOKEN_VALID_DURATION', 60);
                break;
        }
        $issuer = env('JWT_ISSUER');
        if (is_null($issuer)) {
            throw new InvalidArgumentException('請先指定權杖簽發機構名稱');
        }

        $fixed_payloads = [
            'iss' => $issuer,
            'sub' => $subject,
            'exp' => $now->copy()->addMinutes($valid_duration)->timestamp,
            'nbf' => $now->copy()->addMinutes(-1)->timestamp,
            'iat' => $now,
            'jti' => $jwt_id,
        ];
        $payloads = array_merge($payloads, $fixed_payloads);

        if ($want_original) {
            return $payloads;
        }

        $payloads = json_encode($payloads);
        $payloads = Utils::base64_url_encode($payloads);

        return $payloads;
    }

    /**
     * 取得 JWT 權杖的秘密 (Secret)
     *
     * @param string $header
     * @param string $payloads
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function generateJWTSecret(string $header, string $payloads): string
    {
        /** @var string|null $secret */
        $secret = env('JWT_SECRET');
        if (is_null($secret)) {
            throw new InvalidArgumentException('請先設定系統 JWT Secret');
        }

        /** @var string|null $algorithm */
        $algorithm = env('JWT_SECRET_ALGORITHM');
        if (is_null($algorithm)) {
            throw new InvalidArgumentException('請先設定系統 JWT Secret 演算法');
        }

        $body = $header.'.'.$payloads;

        return hash_hmac(strtolower($algorithm), $body, $secret, false);
    }

    /**
     * 驗證並取得存取權杖資訊
     *
     * @param string $jwt_token 存取權杖
     * @return array<string,mixed> [$account, $session, $first_signin]
     *
     * @throws \InvalidArgumentException
     */
    public function decodeJWTToken(string $jwt_token): array
    {
        $jwt_token = base64_decode($jwt_token);
        $jwt_token = explode('.', $jwt_token);
        if (count($jwt_token) != 3) {
            throw new InvalidArgumentException('驗證失敗');
        }
        [$headers, $payloads, $secret] = $jwt_token;
        unset($jwt_token);

        $calculated_secret = $this->generateJWTSecret($headers, $payloads);
        if ($calculated_secret !== $secret) {
            throw new InvalidArgumentException('驗證失敗');
        }
        unset($calculated_secret, $secret);

        $headers = Utils::base64_url_decode($headers);
        $payloads = Utils::base64_url_decode($payloads);

        if ($headers === false || $payloads === false) {
            throw new InvalidArgumentException('驗證失敗');
        }

        $payloads = json_decode($payloads, true);
        $expired_at = Carbon::parse($payloads['exp']);
        if (Carbon::now()->diffInMicroseconds($expired_at, false) < 0) {
            throw new InvalidArgumentException('驗證失敗');
        }

        $not_before = Carbon::parse($payloads['nbf']);
        if (Carbon::now()->diffInMicroseconds($not_before, false) > 0) {
            throw new InvalidArgumentException('驗證失敗');
        }

        $issuer = env('JWT_ISSUER');
        if (is_null($issuer)) {
            throw new InvalidArgumentException('請先指定權杖簽發機構名稱');
        }

        if ($payloads['iss'] !== $issuer) {
            throw new InvalidArgumentException('驗證失敗');
        }

        return $payloads;
    }
}
