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
            $table->char('deposit_at', 1)->comment('0(일) 1(월) 2(화) 3(수) 4(목) 5(금) 6(토)');
            $table->char('category', 1)->comment('0 : 적금, 1 : 이자');
            $table->char('principal', 6);
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
