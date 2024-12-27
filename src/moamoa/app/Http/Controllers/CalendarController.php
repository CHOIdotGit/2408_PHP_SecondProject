<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
use App\Models\Transaction;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function index(Request $request) {
        // 자녀 달력 페이지
        $parent = Auth::guard('parents')->user();
        $child = Auth::guard('children')->user();
        Log::debug($request->all());
        $targetDate = Carbon::createFromDate($request->year, $request->month, 1);

        $startDate = $targetDate->startOfMonth()->format('Y-m-d');
        $endDate =  $targetDate->endOfMonth()->format('Y-m-d');


        $calendarData = Child::select('children.name', 'children.nick_name')
                            ->where('children.parent_id', 1)
                            ->first();
        
        // 부모 예시
        // $calendarData = Child::select('children.name', 'children.nick_name')
        //                     ->where('children.parent_id', $parent->parent_id)
        //                     ->first();
        
        // 자녀 예시
        // $calendarData = Child::select('children.name', 'children.nick_name')
        //                     ->where('children.child_id', $child->child_id)
        //                     ->first();
                            
        $categories = [0 => 'traffic', 1 => 'meal', 2 => 'shopping', 3 => 'etc'];
        $sidebarData = [];

        // 카테고리 별 합산
        foreach($categories as $category => $key){
            $sidebarData[$key] = Transaction::where('parent_id', 1)
            ->where('category', $category)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');
        }

        // 일별 전체 지출 합산
        $dailyIncomeData = Mission::selectRaw('start_at as target_at, SUM(amount) as income')
                                ->whereBetween('start_at', [$startDate, $endDate])
                                ->groupBy('start_at')
                                ->orderBy('start_at')
                                ->get();

        // 일별 전체 지출 합산
        $dailyOutgoData = Transaction::selectRaw('transaction_date as target_at, SUM(amount) as income')
                                ->whereBetween('transaction_date', [$startDate, $endDate])
                                ->groupBy('transaction_date')
                                ->orderBy('transaction_date')
                                ->get();

        // 미션 합계
        $sidebarMission = Mission::where('parent_id',1)
                    ->whereBetween('start_at',[$startDate, $endDate])
                    ->sum('amount');

        // // 모달 지출 내역
        // $usageModal = Transaction::select()

        // 자녀 로그인 확인
        $child = Auth::guard('children')->user();
        $childInfo = Child::select('children.child_id', 'children.name')
                                ->where('children.child_id', $child->child_id)
                                ->first();
                                
 
        

        return response()->json([
            'success' => true,
            'msg' => '캘린더 성공',
            'calendarData' => $calendarData,
            'sidebarData' => $sidebarData,
            'sidebarMission' => $sidebarMission,
            'dailyIncomeData' => $dailyIncomeData,
            'dailyOutgoData' => $dailyOutgoData,        
        ], 200);
    }
    public function show(Request $request) {
        // 자녀 달력 모달 설정
        // 쿼리 파라미터로 year, month 를 받기
        $year = $request->input('year');
        $month = $request->input('month');
        
        // 유효성 검사
        if (!$year || !$month) {
            return response()->json(['message' => 'year, month를 모두 제공해야 합니다.'], 400);
        }

        // 날짜 계산 (예: 2024-12-25 형식으로 변환)
        $date = Carbon::create($year, $month);

        // 자녀 정보 가져오기
        $child = Auth::guard('children')->user();
        $childInfo = Child::select('children.child_id', 'children.name')
                        ->where('children.child_id', $child->child_id)
                        ->first();

        $transactions = $childInfo->transactions()
                                ->select('transactions.transaction_id', 'transactions.title', 'transactions.category', 'transactions.transaction_code', 'transactions.amount', 'transactions.transaction_date')
                                ->whereNull('transactions.deleted_at')
                                ->where('transactions.transaction_date', $date) // 날짜 조건 적용
                                ->get();

        return response()->json([
            'success' => true,
            'msg' => '캘린더 성공',
            'transactions' => $transactions
        ], 200);
    }


        // public function getSidebarData(Request $request)
        // {
        //     $validator = Validator::make($request->all(), [
        //         'year' => 'required|integer',
        //         'month' => 'required|integer|min:1|max:12',
        //     ]);

            
        // }
}