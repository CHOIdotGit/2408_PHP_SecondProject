<?php

namespace App\Http\Controllers;

use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildSavingController extends Controller
{
    // 자녀가 가입한 적금 상품 보여주기
    public function index() {
        // /child/moabank 에서 보여지는 가입 적금 리스트
        // 로그인 유저가 자녀인지 확인
        $child = Auth::guard('children')->user();

        $childSavingList = SavingSignUp::select('child_id', 'saving_sign_up_id', 'saving_product_id')
                                        ->where('child_id', $child->child_id)
                                        ->orderBy('created_at', 'DESC')
                                        ->with([
                                            'saving_products' => function ($query) {
                                                $query
                                                    ->select('saving_product_id', 'saving_product_name', 'saving_product_interest_rate');
                                                    
                                            }
                                        ])

                                        
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
