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
        Schema::create('role_abilities', function (Blueprint $table) {
            $table->comment('角色權限對應表');

            $table->id();
            $table->bigInteger('role_id')->unsigned()->comment('角色 PK');
            $table->bigInteger('ability_id')->unsigned()->comment('權限 PK');
            $table->dateTime('created_at')->nullable()->comment('建立時間');
            $table->dateTime('updated_at')->nullable()->comment('最後更新時間');

            $table->foreign('role_id')->references('id')->on('roles')->restrictOnUpdate()->restrictOnDelete();
            $table->foreign('ability_id')->references('id')->on('abilities')->restrictOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_abilities');
    }
};
