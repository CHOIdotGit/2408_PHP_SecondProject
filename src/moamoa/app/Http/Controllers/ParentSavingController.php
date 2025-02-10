<?php

namespace App\Http\Controllers;

use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentSavingController extends Controller
{
    // ****** 자녀가 가입한 적금상품 리스트 ******/
    public function index() {
        $parent = Auth::guard('parents')->user();

        $savingList = SavingSignUp::select('child_id')
                                    ->where('parent_id', $parent->parent_id)
                                    ->with([
                                        'savingProduct' => function ($query) {
                                            $query->select('saving_product_id', 'saving_product_name');
                                        }
                                    ])
                                    ->with([
                                        'savingDetail' => function ($query) {
                                            $query->select('saving_detail_left')
                                                ->orderBy('created_at', 'DESC');

                                        }
                                    ])
                                    ->get();
        $responseData = [
            'success' => true
            , 'msg' => '적금상품리스트 획득 성공'
            , 'savingList' => $savingList->toArray()
        ];
        return response()->json($responseData, 200);
    }
}
