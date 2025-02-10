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
        // $parent = Auth::guard('parents')->user();

        $childPointList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')                            
                                    ->where('points.child_id', $id)
                                    // ->with('child')
                                    ->with(['child' => function ($query) {
                                        $query->select('children.child_id', 'children.name', 'children.profile', 'children.created_at');
                                    }])
                                    ->orderBy('points.payment_at')
                                    ->latest()
                                    ->paginate(20);
        // $child = Child::where('child_id', 1)->first();
        // $childPointList = $child->points()->get();

        $totalPoint = Point::where('child_id', $id)->where('point_code', '!=', 3)->sum('point');

        $responseData = [
            'success' => true
            ,'msg' => '자녀 포인트 목록 획득 성공'
            ,'childPointList' => $childPointList
            ,'totalPoint' => $totalPoint
        ];
        return response()->json($responseData, 200);
    }
}
