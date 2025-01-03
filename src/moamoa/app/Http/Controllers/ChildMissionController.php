<?php

namespace App\Http\Controllers;

use App\Models\Child;
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
                                    // ->with('child')
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

    // ************************************************
    // **********자녀 미션 상세 페이지 불러오기**********
    // ************************************************
    public function show($mission_id) {
        // $parent = ParentModel::find(1);
        
        $MissionDetail = Mission::find($mission_id);

        
        $responseData = [
            'success' => true
            ,'msg' => '자식 미션 상세 불러오기 성공'
            ,'missionDetail' => $MissionDetail->toArray()
        
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // **************자녀 미션 작성 페이지 **************
    // ************************************************
    public function store(Request $request) {
        
        $child = Auth::guard('children')->user();

        $insertMission = [
            'parent_id' => $request->parent_id
            ,'child_id' => $child->child_id
            ,'title' => $request->title
            ,'category' => $request->category
            ,'content' => $request->content
            ,'amount' => $request->amount
            ,'start_at' => $request->start_at
            ,'end_at' => $request->end_at
        ];

        $missionDetail = Mission::create($insertMission);

        $responseData = [
            'success' => true
            ,'msg' => '미션 등록 성공'
            ,'missionDetail' => $missionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }
}
