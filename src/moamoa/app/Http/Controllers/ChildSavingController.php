<?php

namespace App\Http\Controllers;

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
            // $parent = Auth::guard('parents')->user();


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
                // ,'bankBook' => $bankBook

            ];
            return response()->json($responseData, 200);
    }
}
