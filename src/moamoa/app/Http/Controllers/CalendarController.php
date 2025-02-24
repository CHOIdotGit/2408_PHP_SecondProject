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
    public function childIndex(Request $request)
    {
        Log::debug($request->all());
        $targetDate = Carbon::createFromDate($request->year, $request->month, 1);

        $startDate = $targetDate->startOfMonth()->format('Y-m-d');
        $endDate =  $targetDate->endOfMonth()->format('Y-m-d');

        // 자녀 정보 가져오기
        $child = Auth::guard('children')->id();

        $calendarData = Child::select('children.child_id', 'children.name', 'children.profile')
                            ->where('children.child_id', $child)
                            ->first();
                                
        $categories = [0 => 'traffic', 1 => 'meal', 2 => 'shopping', 3 => 'etc'];
        $sidebarData = [];

        // 카테고리 별 합산 (updated_at 기준)
        foreach($categories as $key => $category){
            $sidebarData[$category] = Transaction::where('transactions.child_id', $child)
                ->where('category', $key)
                ->whereBetween('transactions.transaction_date', [$startDate, $endDate]) // updated_at 사용
                ->sum('amount');
        }

        // 일별 전체 수입 합산 (updated_at 기준)
        // $dailyIncomeData = Transaction::selectRaw('transactions.transaction_date as target_at, SUM(amount) as income') // start_at -> updated_at
        //                         ->where('child_id', $child->child_id)
        //                         ->whereBetween('transactions.transaction_date', [$startDate, $endDate]) // updated_at 사용
        //                         ->groupBy('transactions.transaction_date') // updated_at 기준으로 그룹화
        //                         ->where('transactions.transaction_code', 0)//
        //                         ->whereNull('transactions.deleted_at')
        //                         ->orderBy('transactions.transaction_date') // updated_at 기준으로 정렬
        //                         ->get();
        $dailyIncomeData = $calendarData->missions()
                                            ->select('missions.mission_id', 'missions.title', 'missions.category', 'missions.amount', 'missions.status', 'missions.updated_at')
                                            ->where('missions.status', 2)
                                            ->whereDate('missions.updated_at', [$startDate, $endDate]) // 날짜 조건 적용
                                            ->get();

        // 일별 전체 지출 합산 (updated_at 기준)
        $dailyOutgoData = Transaction::selectRaw('transactions.transaction_date as target_at, SUM(amount) as outgo') // transaction_date -> updated_at
                                    ->where('child_id', $child)
                                    ->whereBetween('transactions.transaction_date', [$startDate, $endDate]) 
                                    ->where('transactions.transaction_code', 1)// updated_at 사용
                                    ->groupBy('transactions.transaction_date') // updated_at 기준으로 그룹화
                                    ->orderBy('transactions.transaction_date') // updated_at 기준으로 정렬
                                    ->get();

        // 미션 합계 (updated_at 기준)
        // $sidebarMission = Transaction::where('transactions.child_id', $child)
        //             ->whereBetween('transactions.transaction_date',[$startDate, $endDate])
        //             ->whereNull('transactions.deleted_at')
        //             ->where('transactions.transaction_code', 0)//
        //             ->sum('transactions.amount');
        $sidebarMission = $calendarData->missions()
                                            ->select('missions.mission_id', 'missions.title', 'missions.category', 'missions.amount', 'missions.status', 'missions.updated_at')
                                            ->where('missions.status', 2)
                                            ->whereDate('missions.updated_at', [$startDate, $endDate])//
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
    
    public function parentIndex(Request $request, $id)
    {
        Log::debug($request->all());
        $targetDate = Carbon::createFromDate($request->year, $request->month, 1);

        $startDate = $targetDate->startOfMonth()->format('Y-m-d');
        $endDate =  $targetDate->endOfMonth()->format('Y-m-d');

        $calendarData = Child::select('children.child_id', 'children.name', 'children.profile')
                            ->where('children.child_id', $id)
                            ->first();

        // 일별 전체 수입 합산 (updated_at 기준)
        $dailyIncomeData = Transaction::selectRaw('transactions.transaction_date as target_at, SUM(amount) as income') // start_at -> updated_at
                                ->whereBetween('transactions.transaction_date', [$startDate, $endDate]) // updated_at 사용
                                ->where('transactions.transaction_code', 0)//
                                ->groupBy('transactions.transaction_date') // updated_at 기준으로 그룹화
                                ->orderBy('transactions.transaction_date')
                                ->where('transactions.child_id', $id) // updated_at 기준으로 정렬
                                ->get();

        // 일별 전체 지출 합산 (updated_at 기준)
        $dailyOutgoData = Transaction::selectRaw('transactions.transaction_date as target_at, SUM(amount) as outgo') // transaction_date -> updated_at
                                    ->whereBetween('transactions.transaction_date', [$startDate, $endDate]) 
                                    ->where('transactions.transaction_code', 1)// updated_at 사용
                                    ->groupBy('transactions.transaction_date') // updated_at 기준으로 그룹화
                                    ->orderBy('transactions.transaction_date')
                                    ->where('transactions.child_id', $id) // updated_at 기준으로 정렬
                                    ->get();

        // // 모달 지출 내역
        // $usageModal = Transaction::select()

        return response()->json([
            'success' => true,
            'msg' => '부모캘린더 성공',
            'calendarData' => $calendarData,
            'dailyIncomeData' => $dailyIncomeData,
            'dailyOutgoData' => $dailyOutgoData,
            
        ], 200);
    }
}