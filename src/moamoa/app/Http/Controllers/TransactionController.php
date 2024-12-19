<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index() {
        // 예제
        $parent = ParentModel::find(1);
        $transactionList = Transaction::select('transactions.child_id', 'transactions.category', 'transactions.title', 'transactions.amount', 'transactions.transaction_date')
                                ->with('child')
                                ->where([
                                    ['transactions.parent_id', $parent->parent_id]
                                    , ['transactions.child_id', 1]
                                    , ['transactions.transaction_code', 1]
                                ])
                                ->latest()
                                ->paginate(15);

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
