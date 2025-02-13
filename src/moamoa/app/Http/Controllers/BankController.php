<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\SavingProduct;
use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $productInfo = SavingSignUp::select('saving_sign_ups.saving_sign_up_id', 'saving_sign_ups.saving_product_id', 'saving_sign_ups.child_id', 'saving_products.saving_product_id'
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

        // 자녀 본인 확인
        $child = Auth::guard('children')->user();
        
         // 기준금리 가져오기 (JSON을 배열로 변환)
        $baseRateResponse = $this->koreaBank(); // JSON 응답 객체
        $baseRate = json_decode($baseRateResponse->getContent(), true); // 배열 변환

        // 기준금리 값 가져오기
        $baseInterest = isset($baseRate['original']['interest']) ? (float)$baseRate['original']['interest'] : 0;

        // 상품 ID에 따라 이자율 변경
        if ($childProductInfo->saving_product_id == 1 || $childProductInfo->saving_product_id == 2) {
            $childProductInfo->saving_product_interest_rate -= $baseInterest; // 기준금리 빼기
        } elseif ($childProductInfo->saving_product_id >= 3 && $childProductInfo->saving_product_id <= 7) {
            $childProductInfo->saving_product_interest_rate += $baseInterest; // 기준금리 더하기
        }

        $responseData = [
            'success' => true
            ,'msg' => '가입한 적금 상품 상세 정보 불러오기 성공'    
            ,'childProductInfo' => $childProductInfo
            ,'baseRate' => $baseRate
        ];

        return response()->json($responseData, 200);
    }

    // public function () {
    //     // 기준금리 가져오기
    //     $baseRate = $this->koreaBank();  

      

    // }

}