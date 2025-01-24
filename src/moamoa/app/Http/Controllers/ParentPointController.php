<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentPointController extends Controller
{
    public function index() {
        // 로그인 유저가 부모인지 확인
        $parent = Auth::guard('parents')->user();

        $childMissionList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')                            
                                    ->with('child')
                                    // ->where('points.child_id', $child->child_id)
                                    ->orderBy('missions.status')
                                    ->latest()
                                    ->paginate(20);
                
        $responseData = [
            'success' => true
            ,'msg' => '미션리스트 획득 성공'
            ,'childMissionList' => $childMissionList->toArray()
        ];
        return response()->json($responseData, 200);
    }
}
