<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentPointController extends Controller
{
    // 부모 포인트 페이지
    public function parent($id) {
        $sort = request('sort', 'desc'); // 기본 정렬은 desc
    
        // 전체 데이터를 정렬한 후, 최신 20개 가져오기
        $childPointList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')
                                ->where('points.child_id', $id)
                                ->with(['child' => function ($query) {
                                    $query->select('children.child_id', 'children.name', 'children.profile', 'children.created_at');
                                }])
                                ->orderBy('points.payment_at', $sort) // 전체 데이터를 정렬
                                ->paginate(20); // 최신 20개를 페이지네이션
    
        $totalPoint = Point::where('child_id', $id)
                            ->where('point_code', '!=', 3)
                            ->sum('point');
    
        return response()->json([
            'success' => true,
            'msg' => '자녀 포인트 목록 획득 성공',
            'childPointList' => $childPointList,
            'totalPoint' => $totalPoint
        ], 200);
    }
    
    
    
    // public function parent($id) {

    //     $childPointList = Point::select('points.child_id', 'points.point', 'points.point_code', 'points.payment_at')                            
    //                                 ->where('points.child_id', $id)
    //                                 // ->with('child')
    //                                 ->with(['child' => function ($query) {
    //                                     $query->select('children.child_id', 'children.name', 'children.profile', 'children.created_at');
    //                                 }])
    //                                 ->orderBy('points.payment_at')
    //                                 ->latest()
    //                                 ->paginate(20);
    //     // $child = Child::where('child_id', 1)->first();
    //     // $childPointList = $child->points()->get();

    //     $totalPoint = Point::where('child_id', $id)->where('point_code', '!=', 3)->sum('point');

    //     $responseData = [
    //         'success' => true
    //         ,'msg' => '자녀 포인트 목록 획득 성공'
    //         ,'childPointList' => $childPointList
    //         ,'totalPoint' => $totalPoint
    //     ];
    //     return response()->json($responseData, 200);
    // }
}
