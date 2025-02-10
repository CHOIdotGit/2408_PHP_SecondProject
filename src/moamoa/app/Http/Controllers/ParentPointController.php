<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentPointController extends Controller
{
    public function index($id) {
        // 로그인 유저가 부모인지 확인
        $parent = Auth::guard('parents')->user();

        $childPointList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')                            
                                    ->where('points.child_id', $id)
                                    ->orderBy('points.payment_at')
                                    // ->with('child' 서브쿼리)
                                    ->latest()
                                    ->paginate(20);
        // $child = Child::where('child_id', 1)->first();
        // $childPointList = $child->points()->get();
        $responseData = [
            'success' => true
            ,'msg' => '미션리스트 획득 성공'
            ,'childPointList' => $childPointList
        ];
        return response()->json($responseData, 200);
    }
}
