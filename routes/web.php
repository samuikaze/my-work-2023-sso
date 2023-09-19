<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    // 外部 API
    $router->group(['middleware' => ['external_logging']], function () use ($router) {
        // API v1
        $router->group(['prefix' => 'v1'], function () use ($router) {
            // 註冊
            $router->post('/user/signup', 'AuthenticateController@signUp');
            // 登入
            $router->post('/user/signin', 'AuthenticateController@signIn');
            // 登出
            $router->post('/user/signout', 'AuthenticateController@signOut');
            // 需要驗證的 API
            $router->group(['middleware' => ['auth_api']], function () use ($router) {
                // 驗證登入狀態，並取得帳號資訊
                $router->get('/user', 'AuthenticateController@authorization');
                // 更新使用者資料
                $router->patch('/user/update', 'AuthenticateController@updateUserData');
                // 以重整權杖重新取得存取權杖
                $router->post('/user/token/refresh', 'AuthenticateController@refreshToken');
                // 產生系統跳轉用存取權杖
                $router->post('/system/token', 'AuthenticateController@generateSystemAccessToken');
            });
            // 依據系統跳轉權杖取得存取權杖與重整權杖
            $router->get('/system/token', 'AuthenticateController@getSignInFromSystemAccessToken');
            // 忘記密碼
            $router->post('/forget/password', 'AuthenticateController@forgetPassword');
            // 取得重設密碼權杖資訊
            $router->get('/reset/password/token', 'AuthenticateController@getResetPasswordInformation');
            // 重設密碼
            $router->post('/reset/password', 'AuthenticateController@resetPassword');
            // 以多筆 ID 取得帳號的名稱
            $router->post('/users', 'InformationController@getUsersInformation');
        });
    });
});
