<?php

namespace App\Services;

use App\Exceptions\EntityNotFoundException;
use App\Mail\ResetPassword;
use App\Repositories\ForgetPasswordRequestRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class SecurityService
{
    /**
     * 是否經由郵件重設密碼
     *
     * @var bool
     */
    protected $reset_password_through_email;

    /**
     * UserRepository
     *
     * @var \App\Repositories\UserRepository
     */
    protected $user_repository;

    /**
     * ForgetPasswordRequestRepository
     *
     * @var \App\Repositories\ForgetPasswordRequestRepository
     */
    protected $forget_password_request_repository;

    public function __construct(
        UserRepository $user_repository,
        ForgetPasswordRequestRepository $forget_password_request_repository
    ) {
        $this->user_repository = $user_repository;
        $this->forget_password_request_repository = $forget_password_request_repository;

        $this->reset_password_through_email = config('singlesignon.reset_password_thru_email', true);
    }

    /**
     * 忘記密碼
     *
     * @param string $email 電子郵件信箱
     * @param string $account 使用者帳號
     * @return string|null
     */
    public function forgetPassword(string $email, string $account)
    {
        try {
            $user = $this->user_repository->findUserByAccountAndEmail($account, $email);
        } catch (EntityNotFoundException $e) {
            throw new InvalidArgumentException('輸入的電子郵件地址或帳號不正確');
        }

        $apply_date = Carbon::now();

        $token = encrypt([
            'user_id' => $user->id,
            'apply_date' => $apply_date,
            'created_at' => $user->created_at,
        ]);

        $record = [
            'email' => $email,
            'token' => $token,
            'created_at' => $apply_date,
        ];
        $record = $this->forget_password_request_repository->create($record);

        $token = $record->token;

        if ($this->reset_password_through_email) {
            $mail = new ResetPassword($record->token);
            Mail::to($email)->send($mail);

            return null;
        }

        return $token;
    }

    /**
     * 重設密碼
     *
     * @param string $email 電子郵件地址
     * @param string $token 權杖
     * @param string $password 新密碼
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function resetPassword(string $email, string $token, string $password): void
    {
        ['user_id' => $user_id] = decrypt($token);
        try {
            $user = $this->user_repository->findUserByAccountOrEmail('', $email);
        } catch (EntityNotFoundException $e) {
            throw new InvalidArgumentException('無效的權杖');
        }

        if ($user->id != $user_id || $user->email != $email) {
            throw new InvalidArgumentException('無效的權杖');
        }

        try {
            $token = $this->forget_password_request_repository->findApplyByEmailAndToken($email, $token);
        } catch (EntityNotFoundException $e) {
            throw new InvalidArgumentException('無效的權杖');
        }

        $password = Hash::make($password);
        $this->user_repository->safeUpdate($user_id, ['password' => $password]);

        $this->forget_password_request_repository->deleteTokensByEmail($user->email);
    }

    /**
     * 取得重設密碼權杖資訊
     *
     * @param string $token 權杖
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function getResetPasswordInformation(string $token): array
    {
        try {
            ['user_id' => $user_id] = decrypt($token);
        } catch (DecryptException $e) {
            throw new InvalidArgumentException('無效的權杖');
        }

        try {
            $user = $this->user_repository->find($user_id);
        } catch (EntityNotFoundException $e) {
            throw new InvalidArgumentException('權杖資訊不完整');
        }

        return $user->only('id', 'account', 'email');
    }
}
