<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Transaction;
use App\Repositories\StatsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatsController extends Controller
{
    protected $statsRepository;

    public function __construct(StatsRepository $statsRepository) {
        $this->statsRepository = $statsRepository;
    }

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
        
        $transactionAmount =Transaction::whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->where('transaction_code', '1')
                                        ->where('child_id', $child_id)
                                        ->max('amount');



        //+=========================================+
        //+  자녀, 가장 자주 사용한 카테고리(최종 수정)     +
        //+=========================================+

        $mostUsedCategory =Transaction::select('category', DB::raw('COUNT(*) cnt'))
                                        ->whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->where('transaction_code', '1')
                                        ->where('child_id', $child_id)
                                        ->groupBy('category')
                                        ->orderBy('cnt', 'DESC')
                                        ->orderBy('transaction_date', 'DESC')
                                        ->first();
        
        //+=========================================+
        //+     자녀, 한달 지출 총합(최종 수정)       +
        //+=========================================+
        $totalAmountChild = Transaction::whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->where('transaction_code', '1')
                                        ->where('child_id', $child_id)
                                        ->sum('amount');

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
        
        //  카테고리별 지출 구하기**
        // $eachCategoryTransaction = Transaction::select(
        //     'category',
        //     DB::raw('SUM(amount) as total_amount')
        // )
        // ->where('parent_id', $parent->parent_id)
        // ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])
        // ->where('child_id', $child_id)
        // // ->where('transaction_code', 1)
        // ->groupBy('category')
        // ->orderBy('category')
        // ->get();
        $eachCategoryTransaction = $this->statsRepository->eachCategoryStats($startOfMonth, $endOfMonth, $child_id);

        // 주간별 지출
        $weeklyOutgoData = DB::select('CALL get_weekly_transaction_report(?, ?, ?)', [$startOfMonth, $endOfMonth, $child_id]);
        

        // 통계 
        $data = [
            'transactions_max_amount' => empty($transactionAmount) ? 0 :(int)$transactionAmount,
            'transactions_max_category' => empty($mostUsedCategory) ? '' : $mostUsedCategory->category,
            'missions_sum_amount' => empty($totalExpenses) ? 0 :(int)$totalExpenses->missions_sum_amount,
            'totalExpenses' => empty($totalAmountChild) ? 0 :(int)$totalAmountChild
        ];

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
