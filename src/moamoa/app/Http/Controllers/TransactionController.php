<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index() {
        // 예제
        $parent = Auth::guard('parents')->user();
        $transactionList = Transaction::select('transactions.parent_id', 'transactions.child_id', 'transactions.category', 'transactions.title', 'transactions.amount', 'transactions.transaction_date', 'transactions.transaction_code')
                                ->with('child')
                                ->where([
                                    ['transactions.parent_id', $parent->parent_id]
                                    , ['transactions.transaction_code', '1']
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
            ,'transactionList' => $transactionList
            // ,'transactionInfo' => $transactionInfo
        ];
        return response()->json($responseData, 200);
    }
}
