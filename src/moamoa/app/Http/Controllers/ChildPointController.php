<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\SavingSignUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildPointController extends Controller
{
    // 자녀 포인트 페이지
    public function child() {
        $child = Auth::guard('children')->id();
        // 전체 데이터를 가져오기 (paginate 없이 get())
        $pointList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')
                                ->where('points.child_id', $child)
                                ->with(['child' => function ($query) {
                                    $query->select('children.child_id', 'children.name', 'children.profile', 'children.created_at');
                                }])
                                ->orderBy('points.payment_at', 'asc') // 오름차순으로 정렬
                                ->orderBy('points.created_at', 'asc') // 오름차순으로 정렬
                                ->get(); // 페이지네이션 없이 전체 데이터를 가져옴
    
        // 누적 계산을 위한 변수 초기화
        $cumulative = 0;
        $deposit = 0;
        $withdrawal = 0;
    
        // 전체 데이터를 순회하며 누적 계산 수행
        $computedList = $pointList->map(function ($item) use (&$cumulative, &$deposit, &$withdrawal) {
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
            'pointList' => $paginatedData, // 페이지별로 나누어진 데이터만 반환
            'totalPoint'   => $totalPoint,
            'deposit'      => $deposit,
            'withdrawal'   => $withdrawal,
            'currentPage'  => (int) $currentPage,
            'lastPage'     => ceil($totalCount / $perPage), // 전체 페이지 수 계산
        ], 200);
    }
    

    // 자녀 은행 페이지
    public function total() {
        $child = Auth::guard('children')->id();

        $totalPoint = Point::select('points.point')
            ->where('points.child_id', $child)
            ->where('points.point_code', '!=', 3)
            ->sum('points.point');  // point_code != 3인 합

        $pointsWithCode3 = Point::select('points.point')
            ->where('points.child_id', $child)
            ->where('points.point_code', 3)
            ->sum('points.point');  // point_code == 3인 합

        $finalTotalPoint = $totalPoint - $pointsWithCode3;

        $savingList = SavingSignUp::select('saving_sign_ups.saving_sign_up_end_at', 'saving_sign_ups.child_id', 'saving_sign_ups.saving_sign_up_status', 'saving_products.saving_product_name')
                                    ->where('saving_sign_ups.saving_sign_up_status', 0)                                                        
                                    ->where('saving_sign_ups.child_id', $child)                                                        
                                    ->join('saving_products', 'saving_sign_ups.saving_product_id', '=', 'saving_products.saving_product_id')
                                    ->get();

        $responseData = [
            'success' => true
            ,'msg' => '포인트 합계, 가입한 적금 상품 리스트 획득 성공'
            ,'totalPoint' => $finalTotalPoint
            ,'savingList' => $savingList
        ];
        return response()->json($responseData, 200);
    }
}
