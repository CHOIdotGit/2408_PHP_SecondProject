<?php

namespace App\Http\Controllers;

use App\Models\SavingProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    // ******** 한국은행 기준 금리 오픈 api ********
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

    
    // ******** 적금 상품 받아오기 ********
    public function savingList() {
        $savingList = SavingProduct::select('saving_product_id', 'saving_product_name', 'saving_product_amount', 'saving_product_interest_rate')
                                    ->paginate(4);

        $responseData = [
            'success' => true
            ,'msg' => '적금 상품 받아오기 성공'
            ,'savingList' => $savingList->toArray()
        ];
        return response()->json($responseData, 200);
    
}


}
