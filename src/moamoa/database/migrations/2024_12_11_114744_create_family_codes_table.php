<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up() {
    Schema::create('family_codes', function (Blueprint $table) {
        $table->id('code_id');
        $table->string('family_code', 30)->unique()->comment('tel 끝자리2 + 랜덤6 (숫자&영대문)');
        $table->timestamps();
        $table->softDeletes();
    });
  }
  
  public function down() {
        Schema::dropIfExists('family_codes');
  }
};
