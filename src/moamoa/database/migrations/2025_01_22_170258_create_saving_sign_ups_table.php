<?php

use App\Models\Child;
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
        Schema::create('saving_sign_ups', function (Blueprint $table) {
            $table->id('saving_sign_up_id');
            $table->unsignedBigInteger('child_id');
            $table->unsignedBigInteger('saving_product_id');
            $table->char('saving_sign_up_deposit_at', 1)->comment('0(일) 1(월) 2(화) 3(수) 4(목) 5(금) 6(토) 7(매일)');
            $table->date('saving_sign_up_start_at');
            $table->date('saving_sign_up_end_at');
            $table->char('saving_sign_up_status', 1)->comment('0 진행중 1 중도 해지 2 만기 해지');
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
        Schema::dropIfExists('saving_sign_ups');
    }
};
