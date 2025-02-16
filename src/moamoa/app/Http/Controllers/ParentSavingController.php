<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SavingProduct;
use Illuminate\Support\Facades\Log;

class ParentSavingController extends Controller
{
    // *****************************
    //  자녀가 가입한 적금상품 리스트 
    // *****************************
    public function index($child_id) {
        $parent = Auth::guard('parents')->user();

        $savingDetail = SavingDetail::all();
        $savingList = SavingSignUp::select('child_id', 'saving_sign_up_id')
                                    ->where('child_id', $child_id)
                                    ->orderBy('created_at', 'DESC')
                                    ->with([
                                        'saving_products' => function ($query) {
                                            $query
                                                ->select('saving_products.saving_product_id', 'saving_products.saving_product_name');
                                                
                                        }
                                    ])
                                    // ->with([
                                    //     'saving_details' => function ($query) {
                                    //         $query
                                    //             ->select('saving_details.saving_detail_id','saving_details.saving_detail_left');
                                                
                                            
                                    //     }
                                    //     ])
                                    ->with('saving_details')
                                    ->get();


    // ****** 자녀 포인트 불러오기 ******/
    // $point = Point::Sum('point')
    //                 ->groupBy('child_id')
    //                 ->get();                 

        $responseData = [
            'success' => true
            , 'msg' => '적금상품리스트 획득 성공'
            , 'savingList' => $savingList
            // , 'point' => $point
            , 'savingDetail' => $savingDetail
        ];
        return response()->json($responseData, 200);
    }

    // ****************************
    // 자녀 적금 통장 상세
    // *****************************
    public function show($bankbook_id) {

        // 로그인 유저가 부모인지 확인
        $parent = Auth::guard('parents')->user();

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
                                ->where('parent_id', $parent->parent_id)
                                ->where('parent_id', $parent->child_id)
                                ->where('saving_sign_ups.saving_sign_up_id', $bankbook_id)
                                ->whereNull('saving_sign_ups.deleted_at')
                                ->get();
            // 자녀가 가입한 통장 정보
            $bankBookInfo = SavingSignUp::select('saving_sign_ups.child_id'
                                                ,'saving_sign_ups.saving_sign_up_start_at'
                                                ,'saving_sign_ups.saving_sign_up_status'
                                                ,'saving_sign_ups.created_at'
                                                )
                                        ->where('parent_id', $parent->parent_id)
                                        ->where('parent_id', $parent->child_id)
                                        ->get();



        $responseData = [
            'success' => true
            , 'msg' => '자녀 통장 내역 획득 성공'
            ,'bankBook' => $bankBook
            ,'bankBookInfo' => $bankBookInfo

        ];
        return response()->json($responseData, 200);
}
}
