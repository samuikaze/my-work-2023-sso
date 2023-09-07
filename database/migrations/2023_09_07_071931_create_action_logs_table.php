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
        Schema::create('action_logs', function (Blueprint $table) {
            $table->comment('存取日誌');

            $table->id();
            $table->string('uri', '512')->comment('來源網址');
            $table->string('method', 8)->comment('HTTP 方法');
            $table->bigInteger('user_id')->unsigned()->nullable()->comment('使用者帳號 PK');
            $table->bigInteger('service_id')->unsigned()->nullable()->comment('系統 PK');
            $table->string('access_ip', 32)->nullable()->comment('存取 IP');
            $table->integer('code')->unsigned()->comment('HTTP 狀態碼');
            $table->json('request_payloads')->nullable()->comment('請求酬載');
            $table->dateTime('created_at')->nullable()->comment('建立時間');
            $table->dateTime('updated_at')->nullable()->comment('最後更新時間');

            $table->foreign('user_id')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->restrictOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_logs');
    }
};
