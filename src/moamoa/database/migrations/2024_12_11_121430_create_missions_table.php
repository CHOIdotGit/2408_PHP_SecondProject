<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::create('missions', function (Blueprint $table) {
      $table->id('mission_id');
      $table->unsignedBigInteger('parent_id');
      $table->unsignedBigInteger('child_id');
      $table->char('category', 1)->comment('0: 학습, 1: 취미, 2: 집안일, 3: 생활습관, 4: 기타');
      $table->string('title', 50);
      $table->string('content');
      $table->bigInteger('amount');
      $table->char('status', 1)->nullable()->default('0')->comment('0: 진행중, 1: 대기중, 2: 완료, 3: 취소');
      $table->date('start_at');
      // $table->date('end_at')->nullable()->default(DB::raw('DATE_ADD(missions.start_at)'))->comment('지정을 따로 하지 않을 경우 일간 미션으로 취급');
      $table->date('end_at')->nullable()->comment('지정을 따로 하지 않을 경우 일간 미션으로 취급');
      $table->char('alarm', 1)->nullable()->default('0')->comment('0: 읽지않음, 1: 읽음');
      $table->timestamps();
      $table->softDeletes();
    });
  }
  
  public function down() {
    Schema::dropIfExists('missions');
  }
};
