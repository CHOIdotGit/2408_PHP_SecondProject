<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::create('transactions', function (Blueprint $table) {
      $table->id('transaction_id');
      $table->unsignedBigInteger('parent_id');
      $table->unsignedBigInteger('child_id');
      $table->char('category', 1)->comment('0: 교통비, 1: 취미, 2:쇼핑, 3:기타');
      $table->char('transaction_code', 1)->comment('0: 수입, 1: 지출');
      $table->string('title', 50);
      $table->bigInteger('amount');
      $table->date('transaction_date');
      $table->string('memo')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }
  
  public function down() {
    Schema::dropIfExists('transactions');
  }
};
