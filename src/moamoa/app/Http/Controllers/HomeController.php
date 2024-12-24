<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {
        $parent = Auth::guard('parents')->user();
        $parentHome = Child::select('children.child_id', 'children.name', 'children.nick_name', 'children.profile')
                                    ->where('children.parent_id', $parent->parent_id)
                                    ->get();
    
    
        foreach($parentHome as $child) {
            $child->setRelation('missions', $child->missions()->latest()->where('missions.status', 1)->limit(3)->get());
            $child->setRelation('transactions', $child->transactions()->latest()->limit(3)->get());
        }

        $totalInfo = Child::select('children.child_id', 'children.name')
                                ->where('children.child_id', $child->child_id)
                                ->first();
        $transactions = $totalInfo->transactions()
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
                    ->whereBetween('missions.created_at', [$startOfMonth, $endOfMonth])
                    ->whereIn('missions.status', ['0', '2'])
                    ->latest()
                    ->limit(6)
                    ->get();

        // transactions 관계에 날짜 조건 추가
        // $transactions = $childHome->transactions()
        //                 ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
        //                 ->latest()
        //                 ->get();

        // 관계 데이터 설정
        $childHome->setRelation('missions', $missions);
        // $childHome->setRelation('transactions', $transactions);

        $responseData = [
            'success' => true
            ,'msg' => '자녀 홈페이지 로드 성공'
            ,'childHome' => $childHome
        ];
        return response()->json($responseData, 200);
    }
}
