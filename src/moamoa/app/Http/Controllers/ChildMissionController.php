<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChildMissionController extends Controller
{
    // ************************************************
    // ****************자녀 미션 리스트 ****************
    // ************************************************
    public function index() {
        // 로그인 유저가 자녀인지 확인
        $child = Auth::guard('children')->user();

        $childMissionList = Mission::select('missions.mission_id', 'missions.child_id', 'missions.status', 'missions.category', 'missions.title', 'missions.amount', 'missions.start_at', 'missions.end_at')                            
                                    ->where('missions.child_id', $child->child_id)
                                    ->whereNull('missions.deleted_at')
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

        $parent = $child->parent_id;//자녀 테이블에서 부모 아이디 확인

        $insertMission = [
            'parent_id' => $parent
            ,'child_id' => $child->child_id
            ,'title' => $request->title
            ,'category' => $request->category
            ,'content' => $request->content
            ,'amount' => $request->amount
            ,'start_at' => $request->start_at
            ,'end_at' => $request->end_at
        ];

        $missionDetail = Mission::create($insertMission);
                                // ->where('parent_id', $request->parent_id);

        $responseData = [
            'success' => true
            ,'msg' => '미션 등록 성공'
            ,'missionDetail' => $missionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // **************자녀 미션 삭제 페이지 **************
    // ************************************************
    public function destroy($mission_id) {

        $deleteMission = Mission::destroy($mission_id);

        $responseData = [
            'success' => true
            ,'msg' => '미션 삭제 성공'
            ,'deleteMission' => $deleteMission
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // **************자녀 미션 수정 페이지 **************
    // ************************************************
    public function update(Request $request) {
        Log::debug('update', $request->all());
        $updateMission = Mission::find($request->mission_id);

        $updateMission->title = $request->title;
        $updateMission->category = $request->category;
        $updateMission->content = $request->content;
        $updateMission->amount = $request->amount;
        $updateMission->start_at = $request->start_at;
        $updateMission->end_at = $request->end_at;

        $updateMission->save();

        $responseData = [
            'success' => true
            ,'msg' => '미션 수정 성공'
            ,'updateMission' => $updateMission
        ];
        return response()->json($responseData, 200);
    }
}
