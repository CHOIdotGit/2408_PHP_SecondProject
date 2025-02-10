<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request, $id) {
        // 예제
        $parent = Auth::guard('parents')->user();
        $transactionList = Transaction::select('transactions.transaction_id', 'transactions.parent_id', 'transactions.child_id', 'transactions.category', 'transactions.title', 'transactions.amount', 'transactions.transaction_date', 'transactions.transaction_code', 'transactions.memo')
                                ->with('child')
                                ->where([
                                    ['transactions.parent_id', $parent->parent_id]
                                    ,['transactions.child_id', $id]
                                    ,['transactions.transaction_code', '1']
                                ])
                                ->orderBy('transactions.transaction_date' ,'DESC')
                                ->paginate(20);

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
            ,'msg' => '지출 정보 획득 성공' 
            ,'transactionList' => $transactionList
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

    // ************************************************
    // ********************필터 검색 *******************
    // ************************************************
    public function search(Request $request) {
        // 부모 확인
        $parent = Auth::guard('parents')->user();

        $category = $request->category;
        $date = $request->transaction_date;
        $keyword = $request->keyword;
        Log::debug('request', $request->all());
        $filters = Transaction::with('child')
        ->where([
            ['transactions.parent_id', $parent->parent_id]
            ,['transactions.child_id', $request->child_id]
            ,['transactions.transaction_code', '1']
        ])
        ->when($category !== '', function($query) use ($category) {
            return $query->where('transactions.category', $category);
        })
        ->when($date, function($query) use ($date) {
            return $query->whereDate('transactions.transaction_date', '=', $date);
        })
        ->when($keyword !== '', function($query) use ($keyword) {
            return $query->where('transactions.title', 'like', '%' . $keyword . '%');
        })
        ->orderBy('transactions.transaction_date' ,'DESC')
        ->paginate(20);


        // if($date) {
        //     $filters->whereDate('start_at', '>=', $date);
        // }
        // if ($keyword) {
        //     $filters->where('title', 'like', '%' . $keyword . '%');
        // }
        // // 페이지네이션 적용
        // $filters = $filters->paginate(20);

        $responseData = [
            'success' => true
            ,'msg' => '필터 성공'
            ,'filters' => $filters
        ];
        
        return response()->json($responseData, 200);
}
}