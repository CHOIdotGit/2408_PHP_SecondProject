<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionChildrenController extends Controller
{
    public function index() {
        $child = Auth::guard('children')->user();
        $childInfo = Child::select('children.child_id', 'children.name')
                        ->where('children.child_id', $child->child_id)
                        ->first();

        $childTransactionList = $childInfo->transactions()
                                        ->select('transactions.transaction_id', 'transactions.category', 'transactions.title', 'transactions.amount', 'transactions.transaction_date')
                                        ->get();
    
    
        $responseData = [
            'success' => true
            ,'msg' => '미션정보 획득 성공'
            ,'childTransactionList' => $childTransactionList
        ];
        return response()->json($responseData, 200);
    }

}
