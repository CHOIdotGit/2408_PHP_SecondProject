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
        Schema::create('savings', function (Blueprint $table) {
            $table->id('saving_id');
            $table->varchar('financial_product', 20)->comment('적금 이름(7일 14일 21일 28일 35일 42일 49일+적금)');
            $table->int('period', 2)->comment('7일 14일 21일 28일 35일 42일 49일');
            $table->bigint('amount', 10)->comment('최소 금액 : 100모아');
            $table->float('interest_rate', 5)->comment('최소 1.5% ~ 최대 8.0% (0.015 ~ 0.08)');
            $table->char('type', 1)->comment('0 : 매일1 : 매주');
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
        Schema::dropIfExists('savings');
    }
};
