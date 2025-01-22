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
        Schema::create('shops', function (Blueprint $table) {
            $table->id('shop_id');
            $table->int('price', 6)->comment('화폐 단위 : 모아');
            $table->varchar('medal', 20)->comment('상점에서 초보 칭호 살 수 있도록 null 허용');
            $table->varchar('theme', 20)->comment('기본 색상이 null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
};
