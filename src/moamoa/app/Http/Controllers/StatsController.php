<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatsController extends Controller
{
    public function index(Request $request, $child_id)
    
    {
        $parent = Auth::guard('parents')->user();
        // $child = Auth::guard('children')->user();

        // 자녀 홈페이지 데이터 불러오기
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // 시작일, 종료일 계산
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay(); // 해당 월의 첫 날
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay(); // 해당 월의 마지막 날

        
        //+=========================================+
        //+      자녀, 가장 큰 지출(최종 수정)        +
        //+=========================================+
        // $transactionAmount = Child::withMax(['transactions' => function($query) use ($startOfMonth, $endOfMonth) {
        //                                 $query
        //                                 ->whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
        //                                 ->where('transactions.transaction_code', '1')
        //                                 ->orderBy('amount', 'DESC'); // 가장 큰 지출
        //                             }], 'amount')
        //                             ->where('parent_id', $parent->parent_id)
        //                             ->where('child_id', $child_id)
        //                             ->first();

        $transactionAmount =Transaction::whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->where('transaction_code', '1')
                                        ->where('child_id', $child_id)
                                        ->max('amount');

        // 자녀 홈, 가장 큰 지출(상민씨 요거 어떻게 수정됐는지 한번 공부해봐요~)
        // $transactionAmount = $childHome->transactions()
        //                         ->select( 'child_id', 'amount')
        //                         ->whereNull('transactions.deleted_at')
        //                         ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        //                         ->orderBy('amount', 'DESC') // 가장 큰 지출
        //                         ->first();

        //+=========================================+
        //+  자녀, 가장 자주 사용한 카테고리(최종 수정)     +
        //+=========================================+
        // $mostUsedCategory = Child::withMax(['transactions' => function($query) use ($startOfMonth, $endOfMonth) {
        //                                 $query
        //                                     ->whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
        //                                     ->where('transactions.transaction_code', '1')
        //                                     ->orderBy('count', 'DESC');
        //                                 }], 'category')
        //                                 ->where('parent_id', $parent->parent_id)
        //                                 ->where('child_id', $child_id)
        //                                 ->first();

        $mostUsedCategory =Transaction::select('category', DB::raw('COUNT(*) cnt'))
                                        ->whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->where('transaction_code', '1')
                                        ->where('child_id', $child_id)
                                        ->groupBy('category')
                                        ->orderBy('cnt', 'DESC')
                                        ->orderBy('transaction_date', 'DESC')
                                        ->first();
        // // 자녀 홈, 가장 많이 사용한 카테고리
        // $mostUsedCategory = $childHome->transactions()
        //                         ->select('transactions.category', DB::raw('COUNT(*) as count')) // 카테고리와 해당 카테고리 개수를 가져옴
        //                         ->whereNull('transactions.deleted_at')
        //                         ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
        //                         ->groupBy('transactions.category') // 카테고리 기준으로 그룹화
        //                         ->orderBy('count', 'DESC') // 사용 횟수 기준으로 내림차순 정렬
        //                         ->first(); // 가장 많이 사용된 카테고리 가져오기

        //+=========================================+
        //+     자녀, 한달 지출 총합(최종 수정)       +
        //+=========================================+
        // $totalAmountChild = Child::withSum(['transactions' => function($query) use ($startOfMonth, $endOfMonth) {
        //                                     $query
        //                                     ->whereBetween('created_at', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
        //                                     ->where('transactions.transaction_code', '1');
        //                                 }], 'amount')
        //                                 ->where('parent_id', $parent->parent_id)
        //                                 ->where('child_id', $child_id)
        //                                 ->first();

        // select(DB::raw('SUM(amount) transactions_sum_amount'))
        //                                 ->
        $totalAmountChild = Transaction::whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->where('transaction_code', '1')
                                        ->where('child_id', $child_id)
                                        ->sum('amount');

        // // // 해당 월(예시, 12월 한 달)의 지출 총 합
        // $totalAmount = $childHome->transactions()
        //                         ->whereNull('transactions.deleted_at')
        //                         ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
        //                         ->sum('transactions.amount');

        //+=========================================+
        //+     자녀, 한달 용돈 총합(최종 수정)       +
        //+=========================================+
        $totalExpenses = Child::withSum(['missions' => function($query) use ($startOfMonth, $endOfMonth) {
                                    $query
                                    ->whereBetween('created_at', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')]);
                                }], 'amount')
                                ->where('parent_id', $parent->parent_id)
                                ->where('child_id', $child_id)
                                ->first();


        // // // 해당 월(예시, 12월 한 달)의 용돈 총 합
        // $totalExpenses = $childHome->missions()
        //                         ->whereNull('missions.deleted_at')
        //                         ->whereBetween('missions.created_at', [$startOfMonth, $endOfMonth])
        //                         ->sum('missions.amount');
        
         //  카테고리별 지출 구하기**
        $eachCategoryTransaction = Transaction::select(
            'category',
            DB::raw('SUM(amount) as total_amount')
        )
        ->where('parent_id', $parent->parent_id)
        ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])
        ->where('child_id', $child_id)
        // ->where('transaction_code', 1)
        ->groupBy('category')
        ->orderBy('category')
        ->get();

        // 주간별 지출
        // $weeklyOutgoData = Transaction::selectRaw(
        //     "DATE_FORMAT(transaction_date, 'w%U') as weeks, SUM(amount) as total"
        // )
        // ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])    
        // ->where('child_id', $child_id)
        // ->where('parent_id', $parent->parent_id)
        // ->groupBy('weeks')
        // ->orderBy('weeks')
        // ->get();

        $weeklyOutgoData = DB::select('CALL myStoredProcedure(?, ?, ?)', [$startOfMonth, $endOfMonth, $child_id]);
        

        // 통계 
        $data = [
            'transactions_max_amount' => (int)$transactionAmount,
            'transactions_max_category' => $mostUsedCategory->category,
            'missions_sum_amount' => (int)$totalExpenses->missions_sum_amount,
            'totalExpenses' => (int)$totalAmountChild
        ];
        // $data = [
        //     'transactions_max_amount' => $transactionAmount->transactions_max_amount,
        //     'transactions_max_category' => $mostUsedCategory->transactions_max_category,
        //     'missions_sum_amount' => $totalExpenses->missions_sum_amount,
        //     'totalExpenses' => $totalAmountChild
        // ];

        $responseData = [
            'success' => true
            ,'msg' => '자녀 홈페이지 로드 성공'
            ,'data' => $data
            ,'eachCategoryTransaction' => $eachCategoryTransaction
            ,'weeklyOutgoData' => $weeklyOutgoData
            

        ];
        
        return response()->json($responseData, 200);
    
    }
}
