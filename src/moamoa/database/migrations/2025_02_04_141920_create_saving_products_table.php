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
        Schema::create('saving_products', function (Blueprint $table) {
            $table->id('saving_product_id');
            $table->string('saving_product_name', 20)->comment('적금 이름(7일 14일 21일 28일 35일 42일 49일 + 적금)');
            $table->char('saving_product_period', 2)->comment('7일 14일 21일 28일 35일 42일 49일');
            $table->bigInteger('saving_product_amount')->comment('최소 금액 : 100모아');
            $table->float('saving_product_interest_rate', 2, 1)->comment('한국은행 기준금리에서 조정');
            $table->char('saving_product_type', 1)->comment('0 : 매일1 : 매주');
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
        Schema::dropIfExists('saving_products');
    }
};
