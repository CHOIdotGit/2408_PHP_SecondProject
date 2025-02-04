<?php

namespace Database\Seeders;

use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SavingDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = [
        //     ['saving_sign_up_id' => 7, 'saving_detail_category' => 0, 'saving_detail_left' => 100, 'saving_detail_income' => 100, 'saving_detail_outcome' => 0], 
        //     ['saving_sign_up_id' => 8, 'saving_detail_category' => 0, 'saving_detail_left' => 100, 'saving_detail_income' => 100, 'saving_detail_outcome' => 0], 
        //     ['saving_sign_up_id' => 9, 'saving_detail_category' => 0, 'saving_detail_left' => 100, 'saving_detail_income' => 100, 'saving_detail_outcome' => 0], 
        // ];

        // saving_sign_ups 테이블의 모든 적금 가입 정보 가져오기
        $savingSignUps = SavingSignUp::all();

        // 각 적금 가입에 대해 처리
        foreach ($savingSignUps as $signUp) {
            // saving_sign_up_start_at(적금 가입일)을 기준으로 날짜 설정
            $startDate = Carbon::parse($signUp->saving_sign_up_start_at);
            $endDate = Carbon::now(); // 오늘 날짜

            // 입금 날짜 정보 (0~7)
            $depositAt = $signUp->saving_sign_up_deposit_at;

            // startDate부터 오늘까지 날짜를 하나씩 증가시키며 처리
            while ($startDate->lte($endDate)) {
                $currentDay = $startDate->dayOfWeek; // 현재 날짜의 요일 (0: 일요일 ~ 6: 토요일)
                // Carbon::dayOfWeek는 0(일요일)에서 6(토요일)까지의 값을 반환함

                // 매일(7일) 입금해야 하는 경우
                if ($depositAt == '7') {
                    SavingDetail::create([
                        'saving_sign_up_id' => $signUp->saving_sign_up_id,
                        'saving_detail_category' => 0,
                        'saving_detail_left' => 100,
                        'saving_detail_income' => 100,
                        'saving_detail_outcome' => 0,
                        // 'created_at' => $currentDay, // 가입날짜로 설정해야 함
                        // 'updated_at' => $currentDay, // 가입날짜로 설정해야 함
                    ]);
                } 
                // 특정 요일(0~6)에 입금하는 경우
                elseif ($currentDay == $depositAt) {
                    SavingDetail::create([
                        'saving_sign_up_id' => $signUp->saving_sign_up_id,
                        'saving_detail_category' => 0,
                        'saving_detail_left' => 100,
                        'saving_detail_income' => 100,
                        'saving_detail_outcome' => 0,
                        // 'created_at' => $currentDay, // 가입날짜로 설정해야 함
                        // 'updated_at' => $currentDay, // 가입날짜로 설정해야 함
                    ]);
                }

                // addDay() 메서드를 사용하여 날짜를 하루씩 증가시킴
                $startDate->addDay();
            }
        }
    }
}
