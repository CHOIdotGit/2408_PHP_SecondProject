<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::table('missions', function (Blueprint $table) {
      $table->foreign('ms_id')->references('ms_id')->on('mission_categories');
    });
  }
  
  public function down() {
    Schema::table('missions', function (Blueprint $table) {
      $table->dropForeign(['ms_id']);
    });
  }
};
