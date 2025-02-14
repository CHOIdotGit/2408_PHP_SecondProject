<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildPointController extends Controller
{
    // ***********************************
    public function index() {
        // 로그인 유저가 자녀인지 확인
        $child = Auth::guard('children')->user();

        if (!$child) {
            return response()->json(['success' => false, 'msg' => '자녀 로그인 정보 없음'], 401);
        }

        $childPoint = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')                            
                                    ->where('points.child_id', $child->child_id)
                                    ->orderBy('points.payment_at', 'asc')
                                    ->paginate(20);

        $totalPoints = Point::where('points.child_id', $child->child_id)->sum('points.point');
                
        $responseData = [
            'success' => true
            ,'msg' => '자녀 포인트 관련 정보 획득 성공'
            ,'childPoint' => $childPoint
            ,'totalPoints' => $totalPoints
        ];
        return response()->json($responseData, 200);
    }
}
