<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::create('parents', function (Blueprint $table) {
      $table->id('parent_id');
      $table->string('account', 20)->unique();
      $table->string('password');
      $table->string('name', 20);
      $table->string('nick_name', 20)->nullable();
      $table->string('email', 100);
      $table->string('tel', 30)->comment('하이픈(-) 제외 숫자만');
      $table->string('profile', 100)->nullable()->default('/profile/default.webp');
      $table->string('family_code', 30)->unique()->comment('tel 끝자리2 + 랜덤6 자리(숫자&영문)');
      $table->timestamps();
      $table->softDeletes()->comment('삭제 시 연결된 관련 데이터도 같이 삭제');
    });
  }
  
  public function down() {
    Schema::dropIfExists('parents');
  }
};
