<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        Log::debug($request->all());
        $targetDate = Carbon::createFromDate($request->year, $request->month, 1);

        $startDate = $targetDate->startOfMonth()->format('Y-m-d');
        $endDate =  $targetDate->endOfMonth()->format('Y-m-d');

        // 자녀 정보 가져오기
        
        $child = Auth::guard('children')->user();

        $calendarData = Child::select('children.child_id', 'children.name', 'children.profile')
                                ->find($child->child_id);
                                
        $categories = [0 => 'traffic', 1 => 'meal', 2 => 'shopping', 3 => 'etc'];
        $sidebarData = [];

        // 카테고리 별 합산 (updated_at 기준)
        foreach($categories as $key => $category){
            $sidebarData[$category] = Transaction::where('transactions.parent_id', $child->child_id)
                ->where('category', $key)
                ->whereBetween('transactions.updated_at', [$startDate, $endDate]) // updated_at 사용
                ->sum('amount');
        }

        // 일별 전체 수입 합산 (updated_at 기준)
        $dailyIncomeData = Transaction::selectRaw('DATE(transactions.transaction_date) as target_at, SUM(amount) as total_income')
                                    ->whereBetween('transactions.transaction_date', [$startDate, $endDate]) // 날짜 범위
                                    ->where('transactions.transaction_code', 0)
                                    ->where('deleted_at IS NULL')
                                    ->groupBy('target_at') // 날짜만 그룹화
                                    ->orderBy('target_at') // 날짜 순 정렬
                                    ->get();

        // 일별 전체 지출 합산 (updated_at 기준)    
        $dailyOutgoData = Transaction::selectRaw('DATE(transactions.transaction_date) as target_at, SUM(amount) as total_outgo')
                                ->whereBetween('transactions.transaction_date', [$startDate, $endDate]) // 날짜 범위
                                ->where('transactions.transaction_code', 1)
                                ->where('deleted_at IS NULL')
                                ->groupBy('target_at') // 날짜만 그룹화
                                ->orderBy('target_at') // 날짜 순 정렬
                                ->get();

        // 미션 합계 (updated_at 기준)
        $sidebarMission = Mission::where('missions.child_id', $child->child_id)
                    ->whereBetween('missions.updated_at',[$startDate, $endDate]) // updated_at 사용
                    ->sum('missions.amount');

        // // 모달 지출 내역
        // $usageModal = Transaction::select()

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
}