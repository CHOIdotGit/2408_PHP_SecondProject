<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
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
            $child->setRelation('missions', $child->missions()->latest()->where('missions.status', 0)->limit(3)->get());
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
    public function show(Request $request) {
        $child = Auth::guard('children')->user();

        if(!$child) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // 자녀 홈페이지 데이터 불러오기
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // 시작일, 종료일 계산
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay(); // 해당 월의 첫 날
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay(); // 해당 월의 마지막 날

        $childHome = Child::select('children.child_id', 'children.name')
                                    ->where('children.child_id', $child->child_id)
                                    ->first();
        // 관계 데이터 추가
        // missions 관계에 날짜 조건 추가
        $missions = $childHome->missions()
                    ->select('missions.mission_id', 'missions.child_id', 'missions.status', 'missions.title')
                    ->whereNull('missions.deleted_at')
                    ->whereBetween('missions.created_at', [$startOfMonth, $endOfMonth])
                    ->whereIn('missions.status', ['0', '2'])
                    ->latest()
                    ->limit(6)
                    ->get();


        /** transactions 관계에 조건 추가 **/ 
        // $transactions = $childHome->transactions()
        //                 ->whereNull('transactions.deleted_at')
        //                 ->get();
                        
        // 자녀 홈, 가장 큰 지출
        $transactionAmount = $childHome->transactions()
                                ->select('transactions.transaction_id', 'transactions.child_id', 'transactions.amount')
                                ->whereNull('transactions.deleted_at')
                                ->where('transactions.transaction_code', '1')
                                ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
                                ->orderBy('transactions.amount', 'DESC') // 가장 큰 지출
                                ->first();

        // 자녀 홈, 가장 많이 사용한 카테고리
        $mostUsedCategory = $childHome->transactions()
                                ->select('transactions.category', DB::raw('COUNT(*) as count')) // 카테고리와 해당 카테고리 개수를 가져옴
                                ->whereNull('transactions.deleted_at')
                                ->where('transactions.transaction_code', '1')
                                ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
                                ->groupBy('transactions.category') // 카테고리 기준으로 그룹화
                                ->orderBy('count', 'DESC') // 사용 횟수 기준으로 내림차순 정렬
                                ->first(); // 가장 많이 사용된 카테고리 가져오기

        // 해당 월(예시, 12월 한 달)의 지출 총 합
        $totalAmount = $childHome->transactions()
                                ->whereNull('transactions.deleted_at')
                                ->where('transactions.transaction_code', '1')
                                ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
                                ->sum('transactions.amount');

        // 해당 월(예시, 12월 한 달)의 용돈 총 합
        $totalExpenses = $childHome->missions()
                                ->whereNull('missions.deleted_at')
                                ->whereBetween('missions.created_at', [$startOfMonth, $endOfMonth])
                                ->sum('missions.amount');
        
        // 관계 데이터 설정
        // $childHome->setRelation('missions', $missions);
            

        $responseData = [
            'success' => true
            ,'msg' => '자녀 홈페이지 로드 성공'
            ,'childHome' => $childHome
            ,'missions' => $missions
            ,'transactionAmount' => $transactionAmount
            ,'mostUsedCategory' => $mostUsedCategory
            ,'totalAmount' => $totalAmount
            ,'totalExpenses' => $totalExpenses
        ];
        return response()->json($responseData, 200);
    }
}
