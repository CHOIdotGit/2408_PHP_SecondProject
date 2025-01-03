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
    public function index(Request $request)
    
    {
        
        $parent = Auth::guard('parents')->user();
        
        // 자녀 홈페이지 데이터 불러오기
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // 시작일, 종료일 계산
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay(); // 해당 월의 첫 날
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay(); // 해당 월의 마지막 날


        // $childHome = Child::select('children.child_id', 'children.name')
        //                             ->where('children.parent_id', $parent->parent_id)
        //                             ->find();
                                    
        $childNameList = Child::select('child_id' , 'name')
                            ->withMax(['transactions' => function($query) use ($startOfMonth, $endOfMonth) {
                                $query
                                ->whereBetween('created_at', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                ->orderBy('amount', 'DESC'); // 가장 큰 지출
                            }], 'amount')
                            ->withMax(['transactions' => function($query) use ($startOfMonth, $endOfMonth) {
                                $query
                                ->select(DB::raw('MAX(amount) total'))
                                ->groupBy('category')
                                ->whereBetween('created_at', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                ->orderBy('count', 'DESC')
                                ->limit(1);
                            }], 'total')
                            // ->
                            ->where('parent_id', $parent->parent_id)
                            ->get();
        
        //+=========================================+
        //+      자녀, 가장 큰 지출(최종 수정)        +
        //+=========================================+
        $transactionAmount = Child::withMax(['transactions' => function($query) use ($startOfMonth, $endOfMonth) {
                                        $query
                                        ->whereBetween('created_at', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->orderBy('amount', 'DESC'); // 가장 큰 지출
                                    }], 'amount')
                                    ->where('parent_id', $parent->parent_id)
                                    ->get();

        // 자녀 홈, 가장 큰 지출(상민씨 요거 어떻게 수정됐는지 한번 공부해봐요~)
        // $transactionAmount = $childHome->transactions()
        //                         ->select( 'child_id', 'amount')
        //                         ->whereNull('transactions.deleted_at')
        //                         ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
        //                         ->orderBy('amount', 'DESC') // 가장 큰 지출
        //                         ->first();

        //+=========================================+
        //+  자녀, 가장 사용한 카테고리(최종 수정)     +
        //+=========================================+
        $mostUsedCategory = Child::withMax(['transactions' => function($query) use ($startOfMonth, $endOfMonth) {
                                        $query
                                        ->whereBetween('created_at', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->orderBy('count', 'DESC');
                                        }], 'category')
                                        ->where('parent_id', $parent->parent_id)
                                        ->get();
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
        $totalAmount = Child::withSum(['transactions' => function($query) use ($startOfMonth, $endOfMonth) {
                                        $query
                                        ->whereBetween('created_at', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')]);
                                        }], 'amount')
                                        ->where('parent_id', $parent->parent_id)
                                        ->get();

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
                                ->get();

        // // // 해당 월(예시, 12월 한 달)의 용돈 총 합
        // $totalExpenses = $childHome->missions()
        //                         ->whereNull('missions.deleted_at')
        //                         ->whereBetween('missions.created_at', [$startOfMonth, $endOfMonth])
        //                         ->sum('missions.amount');
        
        
        $responseData = [
            'success' => true
            ,'msg' => '자녀 홈페이지 로드 성공'
            ,'childNameList' => $childNameList
            ,'transactionAmount' => $transactionAmount
            ,'mostUsedCategory' => $mostUsedCategory
            ,'totalAmount' => $totalAmount
            ,'totalExpenses' => $totalExpenses
        ];
        return response()->json($responseData, 200);

    
    }
}
