<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::create('mission_categories', function (Blueprint $table) {
      $table->id('ms_id');
      $table->char('ms_type', 1)->unique()->comment('순번을 문자열 숫자로 기입');
      $table->string('ms_name', 20);
      $table->string('ms_img', 100)->comment('아이콘의 src를 기입');
      $table->timestamps();
      $table->softDeletes();
    });
  }
  
  public function down() {
    Schema::dropIfExists('mission_categories');
  }
};
