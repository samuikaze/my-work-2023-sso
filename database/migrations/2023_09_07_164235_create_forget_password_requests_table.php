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
        Schema::create('forget_password_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email', 256)->comment('電子郵件信箱');
            $table->string('token', 1024)->comment('重設密碼權杖');
            $table->dateTime('created_at')->nullable()->comment('建立時間');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forget_password_requests');
    }
};
