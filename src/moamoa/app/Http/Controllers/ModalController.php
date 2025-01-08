<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModalController extends Controller
{
    public function show(Request $request) {
        // 자녀 달력 모달 설정

        // 날짜 설정
        $date = $request->input('date');
        
        // 유효성 검사
        if (!$date) {
            return response()->json(['message' => 'date를 모두 제공해야 합니다.'], 400);
        }

        // 자녀 정보 가져오기
        $child = Auth::guard('children')->user();
        $childInfo = Child::select('children.child_id', 'children.name')
                        ->where('children.child_id', $child->child_id)
                        ->first();

        // 수입
        $transactions = $childInfo->transactions()
                                ->select('transactions.transaction_id', 'transactions.title', 'transactions.category', 'transactions.transaction_code', 'transactions.amount', 'transactions.transaction_date')
                                ->where('transactions.transaction_code', 0)
                                ->whereDate('transactions.transaction_date', $date) // 날짜 조건 적용
                                ->get();


        // 지출
        $transactions = $childInfo->transactions()
                                ->select('transactions.transaction_id', 'transactions.title', 'transactions.category', 'transactions.transaction_code', 'transactions.amount', 'transactions.transaction_date')
                                ->where('transactions.transaction_code', 1)
                                ->whereDate('transactions.transaction_date', $date) // 날짜 조건 적용
                                ->get();

        return response()->json([
            'success' => true,
            'msg' => '모달 정보 획득 성공',
            'transactions' => $transactions,
        ], 200);
    }
}
