<?php

namespace App\Jobs;

use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoSavingRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $savingSignUp;
    public function __construct(SavingSignUp $savingSignUp)
    {
        $this->savingSignUp = $savingSignUp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        // 적금 가입하면 자동이체 내역을 통장에 자동으로 기록
        SavingDetail::create([
            'saving_sign_up_id'=> $this->savingSignUp->saving_sign_up_id
            ,'saving_detail_category'=> "0" //0 =적금
            ,'saving_detail_left' => $this->savingSignUp->saving_sign_up_amount
            ,'saving_detail_income' => $this->savingSignUp->saving_sign_up_amount
            ,'saving_detail_outcome' => "0" //출금금액 없음
        ]);

    }
}
