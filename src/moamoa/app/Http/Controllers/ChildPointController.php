<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildPointController extends Controller
{
    // 자녀 포인트 페이지
    public function child() {
        $child = Auth::guard('children')->id();

        $pointList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')                            
                                ->with(['child' => function ($query) {
                                    $query->select('children.child_id', 'children.name', 'children.profile', 'children.created_at');
                                }])
                                ->where('points.child_id', $child)
                                ->orderBy('points.payment_at', 'desc')
                                ->paginate(20);

        $deposit = Point::where('points.child_id', $child)->where('point_code', '!=', 3)->sum('point');
        
        $withdrawal = Point::where('points.child_id', $child)->where('point_code', '=', 3)->sum('point');
                            
        $childTotalPoint = $deposit - $withdrawal;

        $responseData = [
            'success' => true
            ,'msg' => '포인트 목록 획득 성공'
            ,'pointList' => $pointList
            ,'childTotalPoint' => $childTotalPoint
            ,'deposit' => $deposit
            ,'withdrawal' => $withdrawal
        ];
        return response()->json($responseData, 200);
    }
    

    // 자녀 은행 페이지
    public function total() {
        $child = Auth::guard('children')->user();

        $totalPoint = Point::select('points.point_id', 'points.child_id', 'points.point_code', 'points.point')
                                ->where('points.child_id', $child->child_id)
                                ->where('points.point_code', '!=', 3)
                                ->sum('points.point');

        $responseData = [
            'success' => true
            ,'msg' => '포인트 합계 획득 성공'
            ,'totalPoint' => $totalPoint
        ];
        return response()->json($responseData, 200);
    }
}
