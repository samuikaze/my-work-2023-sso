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
        Schema::create('services', function (Blueprint $table) {
            $table->comment('系統服務');

            $table->id();
            $table->string('name', 32)->comment('服務名稱');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('服務狀態，0: 無效，1: 有效');
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
        Schema::dropIfExists('services');
    }
};
