<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::table('incomes', function (Blueprint $table) {
      $table->foreign('in_id')->references('in_id')->on('income_categories');
    });
  }
  
  public function down() {
    Schema::table('incomes', function (Blueprint $table) {
      $table->dropForeign(['in_id']);
    });
  }
};
