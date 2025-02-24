<?php

namespace App\Jobs;

use App\Http\Controllers\BankController;
use App\Models\Point;
use App\Models\SavingDetail;
use App\Models\SavingProduct;
use App\Models\SavingSignUp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoSavingFinish extends MyJob
{
   

    /**
     * Create a new job instance.
     *
     * @return void
     */

    // 계산 로직을 잡 내부에 정의 (컨트롤러 코드를 그대로 복사하고, JSON 응답 대신 배열 반환)
    private function calculateFinalAmountDirect($productId)
    {
        // 1. 상품 정보 조회
        $product = SavingProduct::find($productId);
        if (!$product) {
            throw new \Exception('Product not found');
        }

        // 2. 상품 정보에서 이자율 가져오기
        $interestRateData = SavingProduct::select('saving_product_id', 'saving_product_interest_rate')
            ->where('saving_product_id', $productId)
            ->first();

        // 3. 상품에 가입된 내역들 가져오기 - 오늘 날짜보다 이전인 항목
        $signUps = SavingSignUp::where('saving_product_id', $productId)
            ->where('saving_sign_up_end_at', '<', now()->toDateString())
            ->get();

        if ($signUps->isEmpty()) {
            throw new \Exception('No sign-up records found for this product');
        }

        // 4. 각 가입 내역에 대해 최종 금액 계산
        $results = [];
        foreach ($signUps as $signUp) {
            // 가입 내역에 대한 납입 내역 가져오기 (적금만 계산)
            $savingDetails = SavingDetail::where('saving_sign_up_id', $signUp->saving_sign_up_id)
                ->where('saving_detail_category', '0')
                ->orderBy('created_at', 'asc')
                ->get();

            // 5. 이자 계산
            $total = 0;
            foreach ($savingDetails as $item) {
                $total = floor(($total + $item->saving_detail_income) * (1 + $interestRateData->saving_product_interest_rate / 100));
            }

            // 결과 배열에 각 가입 내역의 최종 금액 추가
            $results[] = [
                'saving_sign_up_id' => $signUp->saving_sign_up_id,
                'final_total'       => $total,
            ];
        }

        return $results;
    }

    public function process()
    {
        $today = Carbon::now()->toDateString();

        $expiredSavings = SavingSignUp::where('saving_sign_up_end_at', '<', $today)
            ->where('saving_sign_up_status', '0')
            ->get();

        if ($expiredSavings->isEmpty()) {
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($expiredSavings as $saving) {
                // 여기서 saving_product_id를 이용하여 최종 금액 계산
                $results = $this->calculateFinalAmountDirect($saving->saving_product_id);
                $result = collect($results)->firstWhere('saving_sign_up_id', $saving->saving_sign_up_id);

                if (!$result) {
                    continue;
                }

                $finalTotal = $result['final_total'];

                // saving_details에 기록
                SavingDetail::create([
                    'saving_sign_up_id'      => $saving->saving_sign_up_id,
                    'saving_detail_left'     => $finalTotal,
                    'saving_detail_category' => '1',
                ]);

                // points 테이블에 기록
                Point::create([
                    'child_id'   => $saving->child_id,
                    'point_code' => '4',
                    'point'      => $finalTotal,
                ]);

                // SavingSignUp의 상태 업데이트: saving_sign_up_status를 '2'로 변경
                $saving->update(['saving_sign_up_status' => '2']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AutoSavingFinish job error: ' . $e->getMessage());
        }
    }

}
