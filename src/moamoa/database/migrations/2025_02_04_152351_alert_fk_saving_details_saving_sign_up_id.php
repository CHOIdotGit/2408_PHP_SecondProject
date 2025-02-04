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
        Schema::table('saving_details', function (Blueprint $table) {
            $table->foreign('saving_sign_up_id')->references('saving_sign_up_id')->on('saving_sign_ups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saving_details', function (Blueprint $table) {
            $table->dropForeign(['saving_sign_up_id']);
        });
    }
};
