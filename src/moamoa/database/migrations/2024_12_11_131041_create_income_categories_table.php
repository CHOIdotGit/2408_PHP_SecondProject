<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::create('income_categories', function (Blueprint $table) {
      $table->id('in_id');
      $table->char('in_type', 1)->unique()->comment('순번을 문자열인 숫자로 기입');
      $table->string('in_name', 20);
      $table->string('in_img', 100)->comment('아이콘의 src를 기입');
      $table->timestamps();
      $table->softDeletes();
    });
  }
  
  public function down() {
    Schema::dropIfExists('income_categories');
  }
};
