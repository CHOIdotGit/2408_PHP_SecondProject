<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildMissionController extends Controller
{
    // ************************************************
    // ****************자녀 미션 리스트 ****************
    // ************************************************
    public function index() {
        // 로그인 유저가 자녀인지 확인
         $child = Auth::guard('children')->user();

        $childMissionList = Mission::select('missions.mission_id', 'missions.child_id', 'missions.status', 'missions.category', 'missions.title', 'missions.amount', 'missions.start_at', 'missions.end_at')
                                    ->with('child')
                                    // ->where('missions.parent_id', $parent->parent_id)
                                    ->where('missions.child_id', $child->child_id)
                                    ->latest()
                                    ->paginate(15);
                
        $responseData = [
            'success' => true
            ,'msg' => '미션리스트 획득 성공'
            ,'childMissionList' => $childMissionList->toArray()
        ];
        return response()->json($responseData, 200);
    }
}
