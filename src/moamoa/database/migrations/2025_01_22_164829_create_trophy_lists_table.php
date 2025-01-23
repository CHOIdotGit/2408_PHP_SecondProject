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
        Schema::create('trophy_lists', function (Blueprint $table) {
            $table->id('trophy_lists_id');
            $table->unsignedBigInteger('trophy_id');
            $table->string('quest_item', 50);
            $table->string('daily_quest_item', 50)->comment('매일 날짜 갱신');
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
        Schema::dropIfExists('trophy_lists');
    }
};
