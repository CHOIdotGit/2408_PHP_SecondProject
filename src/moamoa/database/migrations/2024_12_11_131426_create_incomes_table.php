<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::create('incomes', function (Blueprint $table) {
      $table->id('income_id');
      $table->unsignedBigInteger('parent_id')->comment('부모의 user_id를 기입');
      $table->unsignedBigInteger('child_id')->comment('자식의 user_id를 기입');
      $table->unsignedBigInteger('in_id');
      $table->char('income_code', 1)->comment('0: 수입, 1: 지출');
      $table->bigInteger('amount');
      $table->date('transaction_date');
      $table->string('memo')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }
  
  public function down() {
    Schema::dropIfExists('incomes');
  }
};
