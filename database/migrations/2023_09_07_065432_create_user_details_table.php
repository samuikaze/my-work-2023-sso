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
        Schema::create('user_details', function (Blueprint $table) {
            $table->comment('使用者帳號詳細資訊');

            $table->id();
            $table->bigInteger('user_id')->unsigned()->comment('使用者帳號 PK');
            $table->string('virtual_avator', 1024)->nullable()->comment('虛擬形象');
            $table->string('username', 128)->comment('使用者名稱');
            $table->dateTime('created_at')->nullable()->comment('建立時間');
            $table->dateTime('updated_at')->nullable()->comment('最後更新時間');

            $table->foreign('user_id', 'user_details_user_id_foreign')->references('id')->on('users')->restrictOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
};
