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
            $table->foreign('saving_product_id')->references('saving_product_id')->on('saving_products')->onDelete('cascade');
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
            $table->dropForeign(['saving_product_id']);
        });
    }
};
