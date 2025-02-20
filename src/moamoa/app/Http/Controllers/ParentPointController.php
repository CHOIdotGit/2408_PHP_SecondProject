<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentPointController extends Controller
{
    // 부모 포인트 페이지
    // public function parent($id) {
    
    //     // 전체 데이터를 정렬한 후, 최신 20개 가져오기
    //     $childPointList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')
    //                             ->where('points.child_id', $id)
    //                             ->with(['child' => function ($query) {
    //                                 $query->select('children.child_id', 'children.name', 'children.profile', 'children.created_at');
    //                             }])
    //                             // ->orderBy('points.payment_at', $sort) // 전체 데이터를 정렬
    //                             ->orderBy('points.payment_at', 'desc') // 전체 데이터를 정렬
    //                             ->paginate(20); // 최신 20개를 페이지네이션
    
    //     $deposit = Point::where('points.child_id', $id)->where('point_code', '!=', 3)->sum('point');

    //     $withdrawal = Point::where('points.child_id', $id)->where('point_code', '=', 3)->sum('point');

    //     $totalPoint = $deposit - $withdrawal;

    //     return response()->json([
    //         'success' => true
    //         ,'msg' => '자녀 포인트 목록 획득 성공'
    //         ,'childPointList' => $childPointList
    //         ,'totalPoint' => $totalPoint
    //         ,'deposit' => $deposit
    //         ,'withdrawal' => $withdrawal
    //     ], 200);
    // }
    public function parent($id) {
    
        // 전체 데이터를 가져오기 (paginate 없이 get())
        $childPointList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')
                                ->where('points.child_id', $id)
                                ->with(['child' => function ($query) {
                                    $query->select('children.child_id', 'children.name', 'children.profile', 'children.created_at');
                                }])
                                ->orderBy('points.payment_at', 'asc') // 오름차순으로 정렬
                                ->get(); // 페이지네이션 없이 전체 데이터를 가져옴
    
        // 누적 계산을 위한 변수 초기화
        $cumulative = 0;
        $deposit = 0;
        $withdrawal = 0;
    
        // 전체 데이터를 순회하며 누적 계산 수행
        $computedList = $childPointList->map(function ($item) use (&$cumulative, &$deposit, &$withdrawal) {
            $isWithdrawal = $item->point_code === '3';
            $amount = (float) $item->point;
    
            if ($isWithdrawal) {
                $cumulative -= $amount;
                $withdrawal += $amount;
            } else {
                $cumulative += $amount;
                $deposit += $amount;
            }
    
            return [
                'child_id'       => $item->child_id,
                'point'          => $item->point,
                'point_code'     => $item->point_code,
                'payment_at'     => $item->payment_at,
                'cumulativeTotal'=> $cumulative, // 누적 잔액
                'deposit'        => $isWithdrawal ? 0 : $amount,
                'withdrawal'     => $isWithdrawal ? $amount : 0,
                'child'          => $item->child // 자녀 정보
            ];
        });
    
        // 내림차순 정렬 (최신 거래가 위로)
        $computedList = $computedList->reverse()->values();
    
        // 페이지네이션을 위한 변수 설정
        $perPage = 20;
        $currentPage = request('page', 1);
        $totalCount = $computedList->count();
    
        // 페이지네이션 적용 (현재 페이지에 맞게 데이터 잘라내기)
        $paginatedData = $computedList->slice(($currentPage - 1) * $perPage, $perPage)->values();
    
        // 총 포인트 계산
        $totalPoint = $deposit - $withdrawal;
    
        // 페이지네이션 처리 후 데이터 반환
        return response()->json([
            'success'      => true,
            'msg'          => '자녀 포인트 목록 획득 성공',
            'childPointList' => $paginatedData, // 페이지별로 나누어진 데이터만 반환
            'totalPoint'   => $totalPoint,
            'deposit'      => $deposit,
            'withdrawal'   => $withdrawal,
            'currentPage'  => (int) $currentPage,
            'lastPage'     => ceil($totalCount / $perPage), // 전체 페이지 수 계산
        ], 200);
    }
}
