<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('code_id');
            $table->string('account', 20)->unique();
            $table->string('password');
            $table->char('auth', 1)->comment('0: 부모, 1: 자녀');
            $table->string('name', 20);
            $table->string('nick_name', 20)->nullable();
            $table->char('gender', 1)->comment('0: 남자, 1: 여자');
            $table->string('email', 100);
            $table->string('tel', 30)->comment('하이픈(-) 제외 숫자만');
            $table->string('profile', 100)->nullable()->default('/profile/default.webp');
            $table->string('refresh_token', 512)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
