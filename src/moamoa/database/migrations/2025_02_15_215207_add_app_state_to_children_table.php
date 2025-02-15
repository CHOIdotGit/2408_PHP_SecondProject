<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::table('children', function (Blueprint $table) {
      $table->char('app_state', 1)->nullable()->default('0')->comment('가입후 부모의 승인 여부, 0: 승인 대기중, 1: 승인');
    });
  }
  
  public function down() {
    Schema::table('children', function (Blueprint $table) {
      $table->dropColumn('app_state');
    });
  }
};
