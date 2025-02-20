<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Point;
use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SavingProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParentSavingController extends Controller
{
    // *****************************
    //  자녀가 가입한 적금상품 리스트 
    // *****************************
    public function index($child_id) {
        $parent = Auth::guard('parents')->user();

        $today = date("Y-m-d");

        // 조인문으로 자녀 id, 적금 상품 이름, 이자율, 잔액 합계 불러오기
        $savingList = SavingSignUp::select('saving_sign_ups.child_id',
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
                                        ->where('child_id', $child_id)
                                        ->whereDate('saving_sign_up_end_at', '>', $today )
                                        ->whereNull('saving_sign_ups.deleted_at')
                                        ->orderBy('saving_sign_ups.created_at', 'DESC')
                                        ->get();


                $responseData = [
                    'success' => true
                    , 'msg' => '적금상품리스트 획득 성공'
                    ,'savingList' => $savingList
                ];
                return response()->json($responseData, 200);
            }

    // ****************************
    // 자녀 적금 통장 상세
    // *****************************
    public function show($saving_sign_up_id) {

        // 로그인 유저가 부모인지 확인
        $parent = Auth::guard('parents')->user();
        $child = Child::select('child_id')
                        ->where('parent_id', $parent->parent_id)
                        ->get();


        // 자녀 적금 통장 내역
        $bankBook = SavingSignUp::select('saving_sign_ups.child_id'
                                        ,'saving_sign_ups.saving_sign_up_id'
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



        $responseData = [
            'success' => true
            , 'msg' => '자녀 통장 내역 획득 성공'
            ,'bankBook' => $bankBook
            ,'bankBookInfo' => $bankBookInfo

        ];
        return response()->json($responseData, 200);
}
}
