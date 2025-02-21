<?php

namespace App\Jobs;

use App\Http\Controllers\BankController;
use App\Models\Point;
use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoSavingFinish implements MyJob
{
   

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function process()
    {
        // 오늘 날짜 (YYYY-MM-DD 형식)
        $today = Carbon::now()->toDateString();

        // 조건에 맞는 SavingSignUp 레코드 조회:
        // - 만기일(saving_sign_up_end_at)이 오늘보다 이전이고
        // - 상태(saving_sign_up_status)가 '0'인 경우
        $expiredSavings = SavingSignUp::where('saving_sign_up_end_at', '<', $today)
            ->where('saving_sign_up_status', '0')
            ->get();

        if ($expiredSavings->isEmpty()) {
            // 처리할 데이터가 없으면 그냥 종료
            return;
        }

        // 이자 계산을 위한 BankController 인스턴스 생성
        // (권장: 계산 로직을 별도의 서비스로 분리)
        $bankController = new BankController();

        // 모든 DB 변경 작업을 트랜잭션으로 묶기
        DB::beginTransaction();
        try {
            foreach ($expiredSavings as $saving) {
                // 1. 이자 계산: BankController의 calculateFinalAmount 메서드를 호출하여 최종 금액을 구함
                //    인자로 해당 SavingSignUp의 고유 ID를 전달 (필요에 따라 모델 자체도 전달 가능)
                $finalTotal = $bankController->calculateFinalAmount($saving->saving_sign_up_id);

                // 2. saving_details 테이블에 기록:
                //    - saving_detail_left 컬럼에 계산된 금액 저장
                //    - saving_detail_category를 '1'로 설정
                SavingDetail::create([
                    'saving_sign_up_id'      => $saving->saving_sign_up_id,
                    'saving_detail_left'     => $finalTotal,
                    'saving_detail_category' => '1',
                    // 추가 컬럼이 있다면 여기에 기록
                ]);

                // 3. points 테이블에 기록:
                //    - child_id, point_code '4', 그리고 계산된 금액(point) 저장
                Point::create([
                    'child_id'   => $saving->child_id,
                    'point_code' => '4',
                    'point'      => $finalTotal,
                    // 추가 컬럼이 필요하면 함께 저장
                ]);

                // 4. SavingSignUp의 상태 업데이트: saving_sign_up_status를 '2'로 변경
                $saving->update(['saving_sign_up_status' => '2']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AutoSavingFinish job error: ' . $e->getMessage());
        }
    }

}
