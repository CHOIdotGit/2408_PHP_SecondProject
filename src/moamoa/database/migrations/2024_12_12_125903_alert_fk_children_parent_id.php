<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    schema::table('children', function (Blueprint $table) {
      $table->foreign('parent_id')->references('parent_id')->on('parents')->onDelete('cascade');
    });
  }
  
  public function down() {
    Schema::table('children', function (Blueprint $table) {
      $table->dropForeign(['parent_id']);
    });
  }
};
