<?php

namespace App\Http\Controllers;

use App\Jobs\AutoSavingRecord;
use App\Models\Child;
use App\Models\Point;
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

        $today = date("Y-m-d");

        // 조인문으로 자녀 id, 적금 상품 이름, 이자율, 잔액 합계 불러오기
        $childSavingList = SavingSignUp::select('saving_sign_ups.child_id',
                                                'saving_sign_ups.saving_sign_up_id',
                                                'saving_sign_ups.created_at',
                                                'saving_sign_ups.saving_sign_up_start_at',
                                                'saving_sign_ups.saving_sign_up_end_at',
                                                'saving_products.saving_product_name',
                                                'saving_products.saving_product_interest_rate',
                                                DB::raw('(
                                                    SELECT SUM(saving_details.saving_detail_left)
                                                    FROM saving_details
                                                    WHERE saving_details.saving_sign_up_id = saving_sign_ups.saving_sign_up_id
                                                ) as total' )
                                            )
                                        ->join('saving_products', 'saving_sign_ups.saving_product_id', '=', 'saving_products.saving_product_id')
                                        ->where('child_id', $child->child_id)
                                        ->whereDate('saving_sign_up_end_at', '>', $today )
                                        ->whereNull('saving_sign_ups.deleted_at')
                                        ->orderBy('saving_sign_ups.created_at', 'DESC')
                                        ->get();


                $responseData = [
                    'success' => true
                    , 'msg' => '적금상품리스트 획득 성공'
                    ,'childSavingList' => $childSavingList
                ];
                return response()->json($responseData, 200);
    
    }
    
    // 자녀 적금 통장 상세
    public function show($saving_sign_up_id) {
            // 로그인 유저가 자녀인지 확인
            $child = Auth::guard('children')->user();


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
                                        ->where('saving_sign_ups.saving_sign_up_id', $saving_sign_up_id)
                                        ->whereNull('saving_sign_ups.deleted_at')
                                        ->get();
                // 자녀가 가입한 통장 정보
                $bankBookInfo = SavingSignUp::select('saving_sign_ups.child_id'
                                                    ,'saving_sign_ups.saving_sign_up_start_at'
                                                    ,'saving_sign_ups.saving_sign_up_status'
                                                    ,'saving_sign_ups.created_at'
                                                    )
                                            ->where('saving_sign_ups.saving_sign_up_id', $saving_sign_up_id)
                                            ->first();

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
    public function store(Request $request, $product_id) {
        Log::debug('request', $request->all());
        $child = Auth::guard('children')->user();
        $today = date("Y-m-d");
        // type = 0(매일) => deposit = 7
        // type = 1(매주) => 0~6
        // 적금 상품 확인


        // 가입한 적금이 3개면 가입불가
        $savingCount = SavingSignUp::where('child_id', $child->child_id)
                                    ->count();

        if($savingCount === 3) {
            return response()
                    ->json(['msg' => '최대 3개의 적금만 가입할 수 있습니다.']);
    
        }


        $savingInfo = SavingProduct::select('saving_product_id'
                                        ,'saving_product_type'
                                        ,'saving_product_interest_rate'
                                        ,'saving_product_period')
                                    ->where('saving_product_id', $product_id)
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

        $savingDetail = [
            'saving_sign_up_id'=> $regist->saving_sign_up_id
            ,'saving_detail_category' => "0"
            ,'saving_detail_left' => $regist->saving_sign_up_amount
            ,'saving_detail_income'=> $regist->saving_sign_up_amount
            ,'saving_detail_outcome' => "0"
        ];

        SavingDetail::create($savingDetail);

        // 포인트 내역에서 출금 기록하기
        $outcome = [
            'child_id' => $child->child_id
            ,'point' => $regist->saving_sign_up_amount
            ,'point_code' => '3' // 자동이체 출금
            ,'payment_at' => date('Y-m-d')
        ];
        $outPoint = new Point($outcome);
        $outPoint->save();
        

        $responseData = [
            'success' => true
            ,'msg' => '미션 등록 성공'
            ,'regist' => $regist->toArray()
        ];

        return response()->json($responseData, 200);
    }

    // 자녀 만기된 적금 가져오기
    // public function expiredSaving() {
        
    //     $child = Auth::guard('children')->id(); // 로그인한 사용자의 데이터만 가져오기 위함
    //     $today = now()->toDateString(); // 오늘 날짜 가져오기

    //     $expiredSavings = SavingSignUp::select('saving_sign_ups.saving_sign_up_id', 'saving_sign_ups.child_id', 'saving_sign_ups.saving_sign_up_end_at', 'saving_sign_ups.saving_sign_up_status')
    //                                     ->where('saving_sign_ups.child_id', $child)
    //                                     ->where('saving_sign_ups.saving_sign_up_end_at', '<', $today) // 만기일이 오늘보다 이전인 데이터 조회
    //                                     ->with(['saving_products' => function($query) {
    //                                         $query->select('saving_product_id', 'saving_product_name'); // saving_sign_up_id도 포함해야 관계가 제대로 연결됨
    //                                     }])
    //                                     ->paginate(20);

    //     $responseData = [
    //         'success' => true
    //         ,'msg' => '만기된 적금 가져오기 성공'    
    //         ,'expiredSavings' => $expiredSavings
    //     ];

    //     return response()->json($responseData, 200);
    // }
    public function expiredSaving() {
        $child = Auth::guard('children')->id(); // 로그인한 사용자의 데이터만 가져오기 위함
        $today = now()->toDateString(); // 오늘 날짜 가져오기
    
        // 만기된 적금 데이터를 전체 조회 (페이지네이션 없이)
        $expiredSavings = SavingSignUp::select(
                'saving_sign_ups.saving_sign_up_id',
                'saving_sign_ups.child_id',
                'saving_sign_ups.saving_sign_up_end_at',
                'saving_sign_ups.saving_sign_up_status',
                'saving_products.saving_product_name'
            )
            ->where('saving_sign_ups.child_id', $child)
            ->where('saving_sign_ups.saving_sign_up_end_at', '<', $today) // 만기일이 오늘보다 이전인 데이터 조회
            ->join('saving_products', 'saving_sign_ups.saving_product_id', '=', 'saving_products.saving_product_id')
            ->get();
    
        // 페이지네이션을 위한 변수 설정
        $perPage = 20;
        $currentPage = request('page', 1);
        $totalCount = $expiredSavings->count();
    
        // 페이지네이션 적용: 현재 페이지에 맞게 데이터를 잘라냄
        $paginatedData = $expiredSavings->slice(($currentPage - 1) * $perPage, $perPage)->values();
    
        $responseData = [
            'success' => true,
            'msg' => '만기된 적금 가져오기 성공',
            'expiredSavings' => $paginatedData, // 현재 페이지에 해당하는 데이터
            'currentPage' => (int) $currentPage,
            'lastPage' => ceil($totalCount / $perPage), // 전체 페이지 수 계산
        ];
    
        return response()->json($responseData, 200);
    }
    
}
