<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModalController extends Controller
{
    // 부모 달력 모달 설정
    public function index(Request $request, $child_id) {
        // 날짜 설정
        $date = $request->input('date');
        
        // 유효성 검사
        if (!$date) {
            return response()->json(['message' => 'date를 모두 제공해야 합니다.'], 400);
        }
    
        // 부모 정보 가져오기
        $parent = Auth::guard('parents')->user();
    
        // 자녀 정보 가져오기 (자녀 ID로 자녀를 찾음)
        $childInfo = Child::select('children.child_id', 'children.name', 'children.parent_id')
                          ->where('children.parent_id', $parent->parent_id)
                          ->where('children.child_id', $child_id) // 자녀 ID로 필터링
                          ->first();
    
        // 자녀 정보가 없을 경우
        if (!$childInfo) {
            return response()->json(['message' => '해당 자녀를 찾을 수 없습니다.'], 404);
        }
    
        // 수입
        $income = $childInfo->missions()
                                ->select('missions.mission_id', 'missions.title', 'missions.category', 'missions.amount', 'missions.status', 'missions.updated_at')
                                ->where('missions.status', 2)
                                ->whereDate('missions.updated_at', $date) // 날짜 조건 적용
                                ->get();
                                // income에서 updated_at을 사용한 이유는 부모가 승인을 했다면, 갱신-수정이 되었을 것이라 판단해서 수정날짜를 가져옴
    
        // 지출
        $expense = $childInfo->transactions()
                                ->select('transactions.transaction_id', 'transactions.title', 'transactions.category', 'transactions.transaction_code', 'transactions.amount', 'transactions.transaction_date')
                                ->where('transactions.transaction_code', 1)
                                ->whereDate('transactions.transaction_date', $date) // 날짜 조건 적용
                                ->get();
    
        return response()->json([
            'success' => true,
            'msg' => '모달 정보 획득 성공',
            'income' => $income,
            'expense' => $expense,
        ], 200);
    }
    

    // 자녀 달력 모달 설정
    public function show(Request $request) {

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
        $income = $childInfo->missions()
                                ->select('missions.mission_id', 'missions.title', 'missions.category', 'missions.amount', 'missions.status', 'missions.updated_at')
                                ->where('missions.status', 2)
                                ->whereDate('missions.updated_at', $date) // 날짜 조건 적용
                                ->get();
        // income에서 updated_at을 사용한 이유는 부모가 승인을 했다면, 갱신-수정이 되었을 것이라 판단해서 수정날짜를 가져옴


        // 지출
        $expense = $childInfo->transactions()
                                ->select('transactions.transaction_id', 'transactions.title', 'transactions.category', 'transactions.transaction_code', 'transactions.amount', 'transactions.transaction_date')
                                ->where('transactions.transaction_code', 1)
                                ->whereDate('transactions.transaction_date', $date) // 날짜 조건 적용
                                ->get();

        return response()->json([
            'success' => true,
            'msg' => '모달 정보 획득 성공',
            'income' => $income,
            'expense' => $expense,
        ], 200);
    }
}
