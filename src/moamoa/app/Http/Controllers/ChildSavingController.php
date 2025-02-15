<?php

namespace App\Http\Controllers;

use App\Jobs\AutoSavingRecord;
use App\Models\SavingDetail;
use App\Models\SavingProduct;
use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChildSavingController extends Controller
{
    // 자녀가 가입한 적금 상품 보여주기
    public function index() {
        // /child/moabank 에서 보여지는 가입 적금 리스트
        // 로그인 유저가 자녀인지 확인
        $child = Auth::guard('children')->user();

        // $childSavingList = SavingSignUp::select('child_id', 'saving_sign_up_id', 'saving_product_id')
        //                                 ->where('child_id', $child->child_id)
        //                                 ->orderBy('created_at', 'DESC')
        //                                 ->with([
        //                                     'saving_products' => function ($query) {
        //                                         $query
        //                                             ->select('saving_product_id', 'saving_product_name', 'saving_product_interest_rate');
                                                    
        //                                     }
        //                                 ])

                                        
        //                                 ->get();

        // 조인문으로 자녀 id, 적금 상품 이름, 이자율, 잔액 합계 불러오기
        $childSavingList = SavingSignUp::select('saving_sign_ups.child_id',
                                                'saving_sign_ups.saving_sign_up_id',
                                                'saving_products.saving_product_name',
                                                'saving_products.saving_product_interest_rate',
                                                'saving_sign_ups.created_at',
                                                DB::raw('(
                                                    SELECT SUM(saving_details.saving_detail_left)
                                                    FROM saving_details
                                                    WHERE saving_details.saving_sign_up_id = saving_sign_ups.saving_sign_up_id
                                                ) as total' )
                                            )
                                        ->join('saving_products', 'saving_sign_ups.saving_product_id', '=', 'saving_products.saving_product_id')
                                        ->where('child_id', $child->child_id)
                                        ->whereNull('saving_sign_ups.deleted_at')
                                        ->orderBy('saving_sign_ups.created_at', 'DESC')
                                        ->get();


        $responseData = [
            'success' => true
            , 'msg' => '적금상품리스트 획득 성공'
            ,'childSavingList' => $childSavingList
            // , 'childTest' => $childTest
        ];
        return response()->json($responseData, 200);
    }
    
    // 자녀 적금 통상 상세
    public function show($bankbook_id) {
            // 로그인 유저가 자녀인지 확인
            $child = Auth::guard('children')->user();

            // 로그인 유저가 부모인지 확인
            $parent = Auth::guard('parents')->user();

            if($child) {
                // 자녀 적금 통장 내역
                $bankBook = SavingSignUp::select('saving_sign_ups.child_id'
                                                ,'saving_products.saving_product_name'
                                                ,'saving_products.saving_product_interest_rate'
                                                ,'saving_products.saving_product_type'
                                                ,'saving_details.saving_detail_left'
                                                ,'saving_details.saving_detail_income'
                                                ,'saving_details.saving_detail_outcome'
                                                ,'saving_details.created_at as saving_detail_created_at'
                                                ,'saving_details.saving_detail_category'
                                                )
                                        ->join('saving_details', 'saving_sign_ups.saving_sign_up_id', '=', 'saving_details.saving_sign_up_id')
                                        ->join('saving_products','saving_sign_ups.saving_product_id', '=', 'saving_products.saving_product_id')
                                        ->where('child_id', $child->child_id)
                                        ->where('saving_sign_ups.saving_sign_up_id', $bankbook_id)
                                        ->whereNull('saving_sign_ups.deleted_at')
                                        ->get();
                // 자녀가 가입한 통장 정보
                $bankBookInfo = SavingSignUp::select('saving_sign_ups.child_id'
                                                    ,'saving_sign_ups.saving_sign_up_start_at'
                                                    ,'saving_sign_ups.saving_sign_up_status'
                                                    ,'saving_sign_ups.created_at'
                                                    )
                                            ->where('child_id', $child->child_id)
                                            ->get();

            }
            else if($parent) {
                
            }





            // $bankBook = SavingDetail::select('saving_details.saving_detail_left'
            //                                 ,'saving_details.saving_detail_income'
            //                                 ,'saving_details.saving_detail_outcome'
            //                                 ,'saving_details.created_at'
            //                                 ,'saving_details.saving_detail_category'
            //                                 )
            //                             ->where('saving_sign_up_id', $id)
            //                             ->get();

            $responseData = [
                'success' => true
                , 'msg' => '통장 내역 획득 성공'
                ,'bankBook' => $bankBook
                ,'bankBookInfo' => $bankBookInfo

            ];
            return response()->json($responseData, 200);
    }

    // ***************************
    // 적금 상품 가입하기
    // ***************************
    public function store(Request $request) {
        $child = Auth::guard('children')->user();
        $parent = $child->parent_id;
        $today = date("Y-m-d");
        // type = 0(매일) => deposit = 7
        // type = 1(매주) => 0~6
        // 적금 상품 확인
        $savingInfo = SavingProduct::select('saving_product_id'
                                        ,'saving_product_type'
                                        ,'saving_product_interest_rate'
                                        ,'saving_product_period')
                                    ->where('saving_product_id', $request->product_id)
                                    ->first();

        $savingPeriod = intval($savingInfo->saving_product_period);
        $endDate = date("Y-m-d", strtotime("+$savingPeriod days", strtotime($today)));

        // 공통
        $savingResit = [
            'child_id' => $child->child_id
            ,'saving_product_id' => $savingInfo->saving_product_id
            ,'saving_sign_up_amount' => $request->saving_sign_up_amount
            ,'saving_sign_up_status' => "0"
            ,'saving_sign_up_start_at'=>$today
            ,'saving_sign_up_end_at' =>$endDate
        ];

        // 매일 적금일 경우
        if($savingInfo->saving_product_type === "0") {
            $savingResit['saving_sign_up_deposit_at'] = "7";
        }

        // 매주 적금일 경우
        else if($savingInfo->saving_product_type === "1") {
            $savingResit['saving_sign_up_deposit_at'] = $request->saving_sign_up_deposit_at;
        }
        $regist = SavingSignUp::create($savingResit);
        $responseData = [
            'success' => true
            ,'msg' => '미션 등록 성공'
            ,'regist' => $regist->toArray()
        ];

        return response()->json($responseData, 200);
    }

    // 자녀 만기된 적금 가져오기
    public function expiredSaving() {
        
        $child = Auth::guard('children')->id(); // 로그인한 사용자의 데이터만 가져오기 위함
        $today = now()->toDateString(); // 오늘 날짜 가져오기

        $expiredSavings = SavingSignUp::select('saving_sign_ups.saving_sign_up_id', 'saving_sign_ups.child_id', 'saving_sign_ups.saving_sign_up_end_at', 'saving_sign_ups.saving_sign_up_status')
                                        ->where('saving_sign_ups.child_id', $child)
                                        ->where('saving_sign_ups.saving_sign_up_end_at', '<', $today) // 만기일이 오늘보다 이전인 데이터 조회
                                        ->with(['saving_products' => function($query) {
                                            $query->select('saving_product_id', 'saving_product_name'); // saving_sign_up_id도 포함해야 관계가 제대로 연결됨
                                        }])
                                        ->paginate(20);

        $responseData = [
            'success' => true
            ,'msg' => '만기된 적금 가져오기 성공'    
            ,'expiredSavings' => $expiredSavings
        ];

        return response()->json($responseData, 200);
    }
}
