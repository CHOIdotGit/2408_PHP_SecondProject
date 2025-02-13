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

        $childTransactionList = Transaction::select('transactions.transaction_id', 'transactions.child_id', 'transactions.category', 'transactions.title', 'transactions.amount', 'transactions.transaction_date', 'transactions.transaction_code')
                                        ->where('transactions.child_id', $child->child_id)
                                        ->where('transactions.transaction_code', '1')
                                        ->with('child')
                                        ->whereNull('transactions.deleted_at')
                                        ->orderBy('transactions.transaction_date', 'DESC')
                                        // ->latest()
                                        ->paginate(20);


        $responseData = [
            'success' => true
            ,'msg' => '지출 정보 획득 성공'
            ,'childTransactionList' => $childTransactionList
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // ********************필터 검색 *******************
    // ************************************************
    public function search(Request $request) {
        // 자녀 확인
        $child = Auth::guard('children')->user();

        $category = $request->category;
        $date = $request->transaction_date;
        $keyword = $request->keyword;
        Log::debug('request', $request->all());
        $filters = Transaction::with('child')
        ->where([
            ['transactions.child_id', $child->child_id]
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

        $responseData = [
            'success' => true
            ,'msg' => '필터 성공'
            ,'filters' => $filters
        ];
        
        return response()->json($responseData, 200);
}

    // 자녀 지출 상세 페이지
    public function show($transaction_id) {
        $transactionDetail = Transaction::find($transaction_id);
    
        $responseData = [
            'success' => true
            ,'msg' => '지출 정보 상세 불러오기 성공'
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
            ,'transaction_code' => $request->transaction_code
        ];

        $transactionDetail = Transaction::create($insertTransaction);

        $responseData = [
            'success' => true
            ,'msg' => '지출 등록 성공'
            ,'transactionDetail' => $transactionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }

    // 자녀 지출 삭제
    public function destroy($transaction_id) {

        $deleteTransaction = Transaction::destroy($transaction_id);

        $responseData = [
            'success' => true
            ,'msg' => '지출 삭제 성공'
            ,'deleteTransaction' => $deleteTransaction
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // ************자녀 선택된 미션만 삭제 **************
    // ************************************************
    public function checkedDestroy(Request $request) {
        // exit;
        $checkedSpendId = $request->spendIds;
        // return response()->json(['checkedMissionId' => $checkedMissionId], 200);
        // return $checkedMissionId;
        // exit;
        $deleteCheckedSpend = Transaction::destroy($checkedSpendId);

        //배열로 받아오는지 아닌지 확인
        // if (!$checkedMissionId || !is_array($checkedMissionId)) {
        //     return response()->json([
        //         'success' => false,
        //         'msg' => '유효하지 않은 요청입니다.'
        //     ], 400); // HTTP 400 에러 반환
        // }

        $responseData = [
            'success' => true
            ,'msg' => '선택된 미션 삭제 성공'
            ,'checkedMissionId' => $checkedSpendId
            ,'삭제된 미션 갯수' => $deleteCheckedSpend
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
            ,'msg' => '지출 수정 성공'
            ,'updateTransaction' => $updateTransaction
        ];
        return response()->json($responseData, 200);
    }

}
