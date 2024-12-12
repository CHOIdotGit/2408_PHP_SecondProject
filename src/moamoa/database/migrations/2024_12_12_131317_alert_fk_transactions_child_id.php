<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::table('transactions', function (Blueprint $table) {
      $table->foreign('child_id')->references('child_id')->on('children')->onDelete('cascade');
    });
  }
  
  public function down() {
    Schema::table('transactions', function (Blueprint $table) {
      $table->dropForeign(['child_id']);
    });
  }
};
