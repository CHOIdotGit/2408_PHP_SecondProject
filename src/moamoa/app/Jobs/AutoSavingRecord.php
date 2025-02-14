<?php

namespace App\Jobs;

use App\Models\Point;
use App\Models\SavingDetail;
use App\Models\SavingSignUp;

class AutoSavingRecord extends MyJob {
    /**
     * Execute the job.
     *
     * @return void
     */
    public function process() {
        

        // 가입 내역 받아오기
        $savingSignUp = SavingSignUp::selectAll();

        $point = Point::groupBy('child_id')
                        ->sum('point');

        if($point >= $savingSignUp->saving_sign_up_amount) {
            // 포인트가 납입 금액보다 많을 경우 정상 처리
            // 납입 후 잔액 계산
            $total = 0;
            foreach($savingSignUp as $item ) {
                $total = ($total + $item->saving_sign_up_amount);
            }
    
            // 적금 가입하면 자동이체 내역을 통장에 자동으로 기록
            SavingDetail::create([
                'saving_sign_up_id' => $savingSignUp->saving_sign_up_id
                ,'saving_detail_category'=> "0" //0 =적금
                ,'saving_detail_left' => $total
                ,'saving_detail_income' => $savingSignUp->saving_sign_up_amount
                ,'saving_detail_outcome' => "0" //출금금액 없음
            ]);
            
        }
        else if($point < $savingSignUp->saving_sign_up_amount) {
            // 포인트 잔액이 부족할 경우 자동이체 처리가 안되고 그 내역을 통장에 기록
            SavingDetail::create([
                'saving_sign_up_id' => $savingSignUp->saving_sign_up_id
                ,'saving_detail_category'=> "0" //0 =적금
                ,'saving_detail_left' => $savingSignUp->saving_sign_up_amount
                ,'saving_detail_income' => "0" //입금금액 없음
                ,'saving_detail_outcome' => "0" //출금금액 없음
            ]);
        }               

    }
}
