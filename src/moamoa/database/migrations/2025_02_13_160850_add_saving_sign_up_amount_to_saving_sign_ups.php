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
        Schema::table('saving_sign_ups', function (Blueprint $table) {
            $table->bigInteger('saving_sign_up_amount')->comment('최소 금액 : 100모아');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saving_sign_ups', function (Blueprint $table) {
            //
        });
    }
};
