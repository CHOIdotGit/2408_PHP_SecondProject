<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\SavingDetail;
use App\Models\SavingProduct;
use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    // *******************************************
    // ******** 한국은행 기준 금리 오픈 api ********
    // *******************************************
    // api 서비스 명
    // 인증키 : env("BANK_KEY")
    // 요청유형(파일형식) : json
    // 언어구분 : kr(한국)
    // 요청시작 건수 
    // 요청 종료건수 
    // 통계표 코드 
    // 주기(M :월) 
    // 검색 시작 일자 : 2024년 12월 부터
    // 검색 종료 일자 
    // ********************************************
    public function koreaBank() {
        $apiKey = env("BANK_KEY");
        $premonth = date("Ym", strtotime("-1 month"));
        $url = "https://ecos.bok.or.kr/api/StatisticSearch/".$apiKey."/json/kr/1/1/722Y001/M/".$premonth."/".$premonth."/0101000";

        Log::debug($url);

        $responseKoreaBank = Http::get($url);    
        Log::debug($responseKoreaBank);    

        $responseKoreaBankArr = $responseKoreaBank->json();
        $responseData = [
            'success' => true
            ,'msg' => '한국은행 기준금리 받아 오기 성공'
            ,'interest' => $responseKoreaBankArr['StatisticSearch']['row'][0]['DATA_VALUE']
        ];
        return response()->json($responseData, 200);
    }

    // ***************************************
    // ******** 은행 적금 상품 받아오기 ********
    // ***************************************
    public function savingList() {
        
        $savingList = SavingProduct::select('saving_product_id', 'saving_product_name', 'saving_product_amount', 'saving_product_interest_rate', 'saving_product_type')
                                    ->paginate(7);

        // 매일 넣는 적금
        $singleSavingList = SavingProduct::select('saving_product_id'
                                                ,'saving_product_name'
                                                ,'saving_product_amount'
                                                ,'saving_product_interest_rate'
                                                ,'saving_product_type'
                                                )
                                            ->where('saving_product_type', '=', '0')
                                            ->get();

        // 매주 넣은 적금                                    
        $weekSavingList = SavingProduct::select('saving_product_id'
                                                ,'saving_product_name'
                                                ,'saving_product_amount'
                                                ,'saving_product_interest_rate'
                                                ,'saving_product_type'
                                                )
                                            ->where('saving_product_type', '=', '1')
                                            ->get();

        $responseData = [
            'success' => true
            ,'msg' => '적금 상품 받아오기 성공'
            ,'savingList' => $savingList->toArray()
            ,'singleSavingList' => $singleSavingList
            ,'weekSavingList' => $weekSavingList
        ];

        return response()->json($responseData, 200);
    
    }

    // 가입한 적금 상품 개수와 포인트 확인 - 부모 페이지
    public function index($id) {
        $productCount = SavingSignUp::where('child_id', $id)->count();

        $point = Point::where('child_id', $id)->where('point_code', '!=', 3)->sum('point');

        // $signUpProduct = SavingSignUp::where('child_id', $id)
        //                                 ->with('saving_products')
        // SavingProduct::select('saving_product_id', 'saving_product_name', 'saving_product_interest_rate')->get();

        // 가입한 적금 상품 상세 정보 불러오기
        // $productInfo = SavingProduct::select('saving_product_id', 'saving_product_name', 'saving_product_period', 'saving_product_amount', 'saving_product_interest_rate', 'saving_product_type')
        $productInfo = SavingSignUp::select('saving_sign_ups.saving_sign_up_id', 'saving_sign_ups.saving_product_id', 'saving_sign_ups.child_id', 'saving_sign_ups.saving_sign_up_end_at', 'saving_products.saving_product_id'
                                            , 'saving_products.saving_product_name', 'saving_products.saving_product_period', 'saving_products.saving_product_amount'
                                            , 'saving_products.saving_product_interest_rate', 'saving_products.saving_product_type')
                                    ->where('saving_sign_ups.child_id', $id)
                                    ->join('saving_products', 'saving_sign_ups.saving_product_id', '=', 'saving_products.saving_product_id')
                                    ->get();


        
        $responseData = [
            'success' => true
            ,'msg' => '적금 상품 개수와 포인트, 가입한 적금 상품 상세 정보 불러오기 성공'    
            ,'productCount' => $productCount
            ,'point' => $point
            ,'productInfo' => $productInfo
        ];
        return response()->json($responseData, 200);
    }

    
    public function product($id) {
        
        $childProductInfo =  SavingProduct::select('saving_product_id', 'saving_product_name', 'saving_product_period', 'saving_product_amount', 'saving_product_interest_rate', 'saving_product_type')
                                        ->where('saving_product_id', $id)
                                        ->first();
        
        // 기준금리 가져오기 (JSON을 배열로 변환)
        $baseRateResponse = $this->koreaBank(); // JSON 응답 객체
        $baseRate = json_decode($baseRateResponse->getContent(), true); // 배열 변환

        // 기준금리 값 가져오기 (올바른 키 사용)
        $baseInterest = isset($baseRate['interest']) ? (float)$baseRate['interest'] : 0;

        // saving_product_type에 따라 계산된 이자율 값을 구함
        if ($childProductInfo->saving_product_type == 0) {
            // type 0 : 기준 금리에서 상품 이자율을 뺀 값
            $computedInterestRate = $baseInterest - $childProductInfo->saving_product_interest_rate;
        } elseif ($childProductInfo->saving_product_type == 1) {
            // type 1 : 기준 금리에 상품 이자율을 더한 값
            $computedInterestRate = $baseInterest + $childProductInfo->saving_product_interest_rate;
        } else {
            // 그 외의 경우 원래 값을 사용하거나 기본값 지정
            $computedInterestRate = $childProductInfo->saving_product_interest_rate;
        }

        $responseData = [
            'success' => true
            ,'msg' => '가입한 적금 상품 상세 정보 불러오기 성공'    
            ,'childProductInfo' => $childProductInfo
            ,'computedInterestRate' => $computedInterestRate // 계산된 이자율 값
            ,'baseRate' => $baseRate
        ];

        return response()->json($responseData, 200);
    }

    // 이자율 계산하는 함수
    public function calculateFinalAmount($productId) {
       
        // // 1. 상품 정보 조회
        $product = SavingProduct::find($productId);

        if (!$product) {
            return response()->json(['success' => false, 'msg' => 'Product not found'], 404);
        }

        // 2. 상품 정보에서 이자율 가져오기
        $interestRate = SavingProduct::select('saving_product_id', 'saving_product_interest_rate')
                                        ->where('saving_product_id', $productId)
                                        ->first();

        // 3. 상품에 가입된 내역들 가져오기 (saving_sign_up_id 기준)
        $signUps = SavingSignUp::where('saving_product_id', $productId)->get();

        if ($signUps->isEmpty()) {
            return response()->json(['success' => false, 'msg' => 'No sign-up records found for this product'], 404);
        }

        // 4. 각 가입 내역에 대해 최종 금액 계산
        $results = [];
        foreach ($signUps as $signUp) {
            // 가입 내역에 대한 납입 내역 가져오기
            $savingDetails = SavingDetail::where('saving_sign_up_id', $signUp->saving_sign_up_id)
                                        ->where('saving_detail_category', '0') // 적금만 계산
                                        ->orderBy('created_at', 'asc')
                                        ->get();

            // 5. 이자 계산
            $total = 0;
            foreach ($savingDetails as $item) {
                $total = floor(($total + $item->saving_detail_income) * (1 + $interestRate->saving_product_interest_rate / 100));
            }

            // 결과 배열에 각 상품별 최종 금액 추가
            $results[] = [
                'saving_sign_up_id' => $signUp->saving_sign_up_id,
                'final_total' => $total,
                // 'interestRate' => $interestRate->saving_product_interest_rate
            ];
        }

        // 6. 결과 반환
        return response()->json([
            'success' => true,
            'msg' => '계산된 최종 금액 반환',
            'results' => $results
        ], 200);
    }
}