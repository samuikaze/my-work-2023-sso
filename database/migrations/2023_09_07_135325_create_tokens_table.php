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
        Schema::create('tokens', function (Blueprint $table) {
            $table->comment('權杖');

            $table->id();
            $table->bigInteger('user_id')->unsigned()->comment('使用者帳號 PK');
            $table->string('token_uuid', 36)->comment('權杖唯一識別碼');
            $table->integer('token_type')->unsigned()->comment('權杖種類，存取權杖: 1，重整權杖: 2');
            $table->string('access_ip', 64)->nullable()->comment('存取 IP');
            $table->string('device', 64)->nullable()->comment('使用裝置');
            $table->dateTime('created_at')->nullable()->comment('建立時間');
            $table->dateTime('updated_at')->nullable()->comment('最後更新時間');

            $table->foreign('user_id')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
            $table->index('token_uuid', 'token_uuid_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokens');
    }
};
