<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Transaction;
use App\Repositories\StatsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $statsRepository;

    public function __construct(StatsRepository $statsRepository) {
        $this->statsRepository = $statsRepository;
    }
    
    public function index() {
        $parent = Auth::guard('parents')->user();
        $parentHome = Child::select('children.child_id', 'children.name', 'children.profile')
                                    ->where('children.parent_id', $parent->parent_id)
                                    ->get();

        // 자녀가 없을 경우 처리
        if ($parentHome->isEmpty()) {
            return response()->json([
                'success' => false,
                'msg' => '등록된 자녀가 없습니다.'
            ], 200); // 자녀가 없을 경우 200 상태 코드
        }
    
        foreach($parentHome as $child) {
            $child->setRelation('missions', $child->missions()->latest()->where('missions.status', 1)->limit(3)->get());
            $child->setRelation('transactions', $child->transactions()->latest()->limit(3)->get());
        }

        // 지출 리스트
        $totalInfo = Child::select('children.child_id', 'children.name')
                                ->where('children.child_id', $child->child_id)
                                ->first();
        $transactions = $totalInfo->transactions()
                        ->whereNull('transactions.deleted_at')
                        ->where('transactions.created_at')
                        ->latest()
                        ->get();

        $totalInfo->setRelation('transactions', $transactions);


        $responseData = [
            'success' => true
            ,'msg' => '홈페이지 로드 성공'
            ,'parentHome' => $parentHome->toArray()
        ];
        return response()->json($responseData, 200);
    }

    // 자녀
    public function show(Request $request) {
        $child_id = Auth::guard('children')->id();

        if(!$child_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // 자녀 홈페이지 데이터 불러오기
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // 시작일, 종료일 계산
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay(); // 해당 월의 첫 날
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay(); // 해당 월의 마지막 날

        $childHome = Child::select('children.child_id', 'children.name', 'children.profile', 'children.login_at')
                                    ->where('children.child_id', $child_id)
                                    ->first();       
       
        // missions 관계에 날짜 조건 추가
        $missions = $childHome->missions()
                    ->select('missions.mission_id', 'missions.child_id', 'missions.status', 'missions.title')
                    ->whereNull('missions.deleted_at')
                    ->whereBetween('missions.created_at', [$startOfMonth, $endOfMonth])
                    ->whereIn('missions.status', ['0', '2'])
                    ->latest()
                    ->limit(6)
                    ->get();

        $savings = $childHome->saving_sign_ups()
                    ->select('saving_sign_ups.saving_sign_up_id', 'saving_sign_ups.saving_sign_up_start_at', 'saving_sign_ups.saving_sign_up_end_at', 'saving_sign_ups.saving_sign_up_status', 'saving_products.saving_product_name')
                    ->join('saving_products', 'saving_sign_ups.saving_product_id', '=', 'saving_products.saving_product_id')
                    ->where('saving_sign_ups.saving_sign_up_status', '0')
                    ->get();

        /** transactions 관계에 조건 추가 **/ 
        // $transactions = $childHome->transactions()
        //                 ->whereNull('transactions.deleted_at')
        //                 ->get();
                        
        // 자녀 홈, 가장 큰 지출
        $transactionAmount = Transaction::whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                    ->where('transaction_code', '1')
                                    ->where('child_id', $child_id)
                                    ->max('amount');

        // 자녀 홈, 가장 많이 사용한 카테고리
        $mostUsedCategory = Transaction::select('category', DB::raw('COUNT(*) cnt'))
                                        ->whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                        ->where('transaction_code', '1')
                                        ->where('child_id', $child_id)
                                        ->groupBy('category')
                                        ->orderBy('cnt', 'DESC')
                                        ->orderBy('transaction_date', 'DESC')
                                        ->first();// 가장 많이 사용된 카테고리 가져오기

        // 해당 월(예시, 12월 한 달)의 용돈 총 합
        $totalAmount = Transaction::whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                    ->where('transaction_code', '0')
                                    // ->where('deleted_at IS NULL')
                                    ->where('child_id',$child_id)
                                    ->sum('amount');

        // 해당 월(예시, 12월 한 달)의 지출 총 합
        $totalExpenses = Transaction::whereBetween('transactions.transaction_date', [$startOfMonth->format('Y-m-d h:i:s'), $endOfMonth->format('Y-m-d h:i:s')])
                                    ->where('transaction_code', '1')
                                    // ->where('deleted_at IS NULL')
                                    ->where('child_id', $child_id)
                                    ->sum('amount');
        // 관계 데이터 설정
        // $childHome->setRelation('missions', $missions);


        // 카테고리 별 지출
        $eachCategoryTransaction = $this->statsRepository->eachCategoryStats($startOfMonth, $endOfMonth, $child_id);

        $data = [
            'transactions_max_amount' => empty($transactionAmount) ? 0 :(int)$transactionAmount,
            'transactions_max_category' => empty($mostUsedCategory) ? '' : $mostUsedCategory->category,
            'missions_sum_amount' => $totalAmount,
            'transactions_sum_amount' => empty($totalExpenses) ? 0 :(int)$totalExpenses
        ];

        $responseData = [
            'success' => true
            ,'msg' => '자녀 홈페이지 로드 성공'
            ,'childHome' => $childHome
            ,'missions' => $missions
            ,'data' => $data
            ,'eachCategoryTransaction' => $eachCategoryTransaction
            ,'savings' => $savings
        ];
        return response()->json($responseData, 200);
    }
}
