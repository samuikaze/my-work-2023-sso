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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->comment('使用者帳號角色對應表');

            $table->id();
            $table->bigInteger('user_id')->unsigned()->comment('使用者帳號 PK');
            $table->bigInteger('role_id')->unsigned()->comment('角色 PK');
            $table->dateTime('created_at')->nullable()->comment('建立時間');
            $table->dateTime('updated_at')->nullable()->comment('最後更新時間');

            $table->foreign('user_id')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->restrictOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
};
