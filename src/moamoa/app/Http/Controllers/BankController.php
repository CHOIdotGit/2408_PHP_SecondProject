<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    // 한국은행 기준 금리 오픈 api
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
        $url = "https://ecos.bok.or.kr/api/StatisticSearch/".$apiKey."/json/kr/1/10/722Y001/M/202301/202401/0101000";
        Log::debug($url);

        $responseKoreaBank = Http::get($url);    
        Log::debug($responseKoreaBank);    

        return $responseKoreaBank;
        
    }
}
