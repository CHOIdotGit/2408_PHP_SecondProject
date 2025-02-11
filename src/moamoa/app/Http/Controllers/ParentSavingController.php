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
    // ****** 자녀가 가입한 적금상품 리스트 ******/
    public function index($child_id) {
        $parent = Auth::guard('parents')->user();

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
        $savingDetail = SavingDetail::selectAll();

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

}
