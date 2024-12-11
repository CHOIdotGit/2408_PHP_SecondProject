<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::table('incomes', function (Blueprint $table) {
      $table->foreign('parent_id')->references('user_id')->on('users')->where('auth', '0');
      $table->foreign('child_id')->references('user_id')->on('users')->where('auth', '1');
    });
  }
  
  public function down() {
    Schema::table('incomes', function (Blueprint $table) {
      $table->dropForeign(['parent_id']);
      $table->dropForeign(['child_id']);
    });
  }
};
