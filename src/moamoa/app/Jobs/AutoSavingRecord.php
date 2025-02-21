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
        $savingSignUp = SavingSignUp::all();

        
        // 선택한 요일에만 자동이체 처리 **********************************
        // 1. 매일 자동이체 처리
        // savingProduct 테이블에 saving_product_type = "0" 일 경우 
        //  saving_deposit_at = 7(매일)
        
        // 2. 매주 자동이체 처리
        // savingProduct 테이블에 saving_product_type = "1" 일 경우
        // saving_sign_up 테이블 saving_deposit_at 요일 선택
        // 0(일), 1(월), 2(화), 3(수), 4(목), 5(금), 6(토)
        // **************************************************************
        $today = date('w');

        foreach ($savingSignUp as $item) {
            $depositDay = $item->saving_sign_up_deposit_at;

            if($depositDay === $today || $depositDay === '7') {

                // **************************************************************
                // 포인트 잔액 확인 후 자동이체 처리
                // **************************************************************
                $point = Point::where('child_id', $item->child_id)
                                ->whereIn('point_code', ['0', '1', '2', '4'])
                                ->sum('point');
        
                if($point >= $item->saving_sign_up_amount) {
                    // 포인트가 납입 금액보다 많을 경우 정상 처리

                    // 납입 후 잔액 계산
                    $total = SavingDetail::where('saving_sign_up_id', $item->saving_sign_up_id)
                                        ->sum('saving_detail_income') + $item->saving_sign_up_amount;

                    // 적금 가입하면 자동이체 내역을 통장에 자동으로 기록
                    SavingDetail::create([
                        'saving_sign_up_id' => $item->saving_sign_up_id
                        ,'saving_detail_category'=> "0" //0 =적금
                        ,'saving_detail_left' => $total
                        ,'saving_detail_income' => $item->saving_sign_up_amount
                        ,'saving_detail_outcome' => "0" //출금금액 없음
                    ]);

                    //포인트 내역에 출금 내역 기록
                    $outcome = [
                        'child_id' => $item->child_id
                        ,'point' => $item->saving_sign_up_amount
                        ,'point_code' => '4'
                        ,'payment_at' => date('Y-m-d')
                    ];
                    $outPoint = new Point($outcome);
                    $outPoint->save();
                }
                else {
                    // 포인트 잔액이 부족할 경우 자동이체 처리가 안되고 그 내역을 통장에 기록
                    SavingDetail::create([
                        'saving_sign_up_id' => $item->saving_sign_up_id
                        ,'saving_detail_category'=> "0" //0 =적금
                        ,'saving_detail_left' => $item->saving_sign_up_amount
                        ,'saving_detail_income' => "0" //입금금액 없음
                        ,'saving_detail_outcome' => "0" //출금금액 없음
                    ]);

                    //포인트 내역에 출금 내역 기록
                    $outcome = [
                        'child_id' => $item->child_id
                        ,'point' => "0"
                        ,'point_code' => '4'
                        ,'payment_at' => date('Y-m-d')
                    ];
                    $outPoint = new Point($outcome);
                    $outPoint->save();
                }
            }
        }
    }
}
