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
        Schema::create('saving_details', function (Blueprint $table) {
            $table->id('saving_detail_id');
            $table->unsignedBigInteger('saving_sign_up_id');
            $table->char('saving_detail_category', 1)->comment('0 : 적금, 1 : 이자');
            $table->integer('saving_detail_left')->comment('잔액');
            $table->BigInteger('saving_detail_income')->comment('맡긴 금액');
            $table->BigInteger('saving_detail_outcome')->comment('출금 금액');
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
        Schema::dropIfExists('saving_details');
    }
};
