<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->comment('使用者帳號');

            $table->id();
            $table->string('account', 64)->comment('使用者帳號');
            $table->string('email', 256)->comment('電子郵件地址');
            $table->string('password', 255)->comment('密碼');
            $table->dateTime('email_verified_at')->nullable()->comment('電子郵件地址驗證時間');
            $table->tinyInteger('status')->default(1)->comment('帳號狀態，0: 無效，1: 有效，2: 停權');
            $table->dateTime('created_at')->nullable()->comment('建立時間');
            $table->dateTime('updated_at')->nullable()->comment('最後更新時間');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
