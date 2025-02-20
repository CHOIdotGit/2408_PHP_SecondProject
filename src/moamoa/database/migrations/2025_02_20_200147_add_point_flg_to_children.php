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
        Schema::table('children', function (Blueprint $table) {
            $table->char('point_flg', 1)->default('0')->comment('0: 포인트  지급 안됨,  1: 포인트 지급 됨');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn('point_flg', 1)->default('0')->comment('0: 포인트  지급 안됨,  1: 포인트 지급 됨');
        });
    }
};
