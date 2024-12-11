<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::table('users', function (Blueprint $table) {
      $table->foreign('code_id')->references('code_id')->on('family_codes');
    });
  }
  
  public function down() {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign(['code_id']);
    });
  }
};
