<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionChildrenController extends Controller
{
    // 자녀 지출 리스트 페이지
    public function index() {
        $child = Auth::guard('children')->user();

        $childTransactionList = Transaction::select('transactions.transaction_id', 'transactions.child_id', 'transactions.category', 'transactions.title', 'transactions.amount', 'transactions.transaction_date')
                                        ->where('transactions.child_id', $child->child_id)
                                        ->whereNull('transactions.deleted_at')
                                        ->latest()
                                        ->paginate(30);
    
    
        $responseData = [
            'success' => true
            ,'msg' => '미션정보 획득 성공'
            ,'childTransactionList' => $childTransactionList
        ];
        return response()->json($responseData, 200);
    }

    // 자녀 지출 상세 페이지
    public function show($transaction_id) {
        $transactionDetail = Transaction::find($transaction_id);
    
        $responseData = [
            'success' => true
            ,'msg' => '미션정보 상세 불러오기 성공'
            ,'transactionDetail' => $transactionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }

    // 자녀 지출 작성 페이지
    public function store(Request $request) {
        
        $child = Auth::guard('children')->user();

        $parent = $child->parent_id;//자녀 테이블에서 부모 아이디 확인

        $insertTransaction = [
            'parent_id' => $parent
            ,'child_id' => $child->child_id
            ,'title' => $request->title
            ,'category' => $request->category
            ,'memo' => $request->memo
            ,'amount' => $request->amount
            ,'transaction_date' => $request->transaction_date
        ];

        $transactionDetail = Transaction::create($insertTransaction);

        $responseData = [
            'success' => true
            ,'msg' => '미션 등록 성공'
            ,'transactionDetail' => $transactionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }

    // 자녀 지출 삭제
    public function destroy($transaction_id) {

        $deleteTransaction = Transaction::destroy($transaction_id);

        $responseData = [
            'success' => true
            ,'msg' => '미션 삭제 성공'
            ,'deleteTransaction' => $deleteTransaction
        ];
        return response()->json($responseData, 200);
    }

    // 자녀 지출 수정 페이지 
    public function update(Request $request) {
        Log::debug('update', $request->all());
        $updateTransaction = Transaction::find($request->transaction_id);

        $updateTransaction->title = $request->title;
        $updateTransaction->category = $request->category;
        $updateTransaction->memo = $request->memo;
        $updateTransaction->amount = $request->amount;
        $updateTransaction->transaction_date = $request->transaction_date;

        $updateTransaction->save();

        $responseData = [
            'success' => true
            ,'msg' => '미션 수정 성공'
            ,'updateTransaction' => $updateTransaction
        ];
        return response()->json($responseData, 200);
    }

}