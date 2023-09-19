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
        Schema::create('service_access_tokens', function (Blueprint $table) {
            $table->comment('服務跳轉權杖');

            $table->id();
            $table->char('token', 50)->comment('跳轉用存取權杖');
            $table->string('access_token', 1024)->comment('存取權杖');
            $table->string('refresh_token', 1024)->comment('重整權杖');
            $table->dateTime('expired_at')->comment('過期時間');
            $table->dateTime('created_at')->nullable()->comment('建立時間');
            $table->dateTime('updated_at')->nullable()->comment('最後更新時間');

            $table->index('token', 'token_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_access_tokens');
    }
};
