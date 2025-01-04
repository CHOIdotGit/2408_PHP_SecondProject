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
    public function index(Request $request)
    {
        Log::debug($request->all());
        $targetDate = Carbon::createFromDate($request->year, $request->month, 1);

        $startDate = $targetDate->startOfMonth()->format('Y-m-d');
        $endDate =  $targetDate->endOfMonth()->format('Y-m-d');


        $calendarData = Child::select('children.name', 'children.nick_name', 'children.profile')
                            ->where('children.parent_id', 1)
                            ->first();
                            
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