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
        Schema::create('points', function (Blueprint $table) {
            $table->id('point_id');
            $table->unsignedBigInteger('child_id');
            $table->integer('point')->default(0);
            $table->char('point_code', 1)->comment('0 : 출석 체크 1 : 미션 수행, 2 : 이자, 3 : 소비, 4 : 도전 과제/ 거래내용 항목');
            $table->date('payment_at');
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
        Schema::dropIfExists('points');
    }
};
