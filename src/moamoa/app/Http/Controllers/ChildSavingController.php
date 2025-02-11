<?php

namespace App\Http\Controllers;

use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
