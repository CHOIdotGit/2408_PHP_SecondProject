<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index($id) {
        // 예제
        $parent = Auth::guard('parents')->user();
        $transactionList = Transaction::select('transactions.transaction_id', 'transactions.parent_id', 'transactions.child_id', 'transactions.category', 'transactions.title', 'transactions.amount', 'transactions.transaction_date', 'transactions.transaction_code', 'transactions.memo')
                                ->with('child')
                                ->where([
                                    ['transactions.parent_id', $parent->parent_id]
                                    , ['transactions.transaction_code', '1']
                                    , ['transactions.child_id', $id]
                                ])
                                ->orderBy('transactions.transaction_date' ,'DESC')
                                ->paginate(30);

        // $transactionInfo = Transaction::select('transactions.child_id', 'transactions.status', 'transactions.category', 'transactions.title', 'transactions.amount', 'transactions.start_at', 'transactions.end_at')
        //                         ->with('child')
        //                         ->where([
        //                             ['transactions.parent_id', $parent->parent_id]
        //                             , ['transactions.child_id', 1]
        //                         ])
        //                         ->latest()
        //                         ->paginate(15);


        $responseData = [
            'success' => true
            ,'msg' => '미션정보 획득 성공' 
            ,'transactionList' => $transactionList->toArray()
        ];
        return response()->json($responseData, 200);
    }

    public function show($transaction_id) {
        $transactionDetail = Transaction::find($transaction_id);
        $responseData = [
            'success' => true
            ,'msg' => '지출 상세 불러오기 성공' 
            ,'transactionDetail' => $transactionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }
}
