<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MissionController extends Controller
{
    public function index($id) {
        // 예제
        $parent = Auth::guard('parents')->user();
       
        $missionList = Mission::select('missions.mission_id', 'missions.child_id', 'missions.status', 'missions.category', 'missions.title', 'missions.amount', 'missions.start_at', 'missions.end_at')
                                    ->with('child')
                                    ->where('missions.parent_id', $parent->parent_id)
                                    ->where('missions.child_id', $id)
                                    ->latest()
                                    ->paginate(15);
                
        $responseData = [
            'success' => true
            ,'msg' => '미션리스트 획득 성공'
            ,'missionList' => $missionList->toArray()
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // **********부모 미션 상세 페이지 불러오기**********
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
    // **************부모 미션 작성 페이지 **************
    // ************************************************
    public function store(Request $request) {
        // 부모 확인
        $parent = Auth::guard('parents')->user();
        
        // 자녀 확인
        $child = child::where('child_id', $request->child_id)
                        ->where('parent_id', $parent->parent_id)
                        ->first();

        if(!$child) {
        return response() ->json(['error'=> '자녀 정보를 찾을 수 없습니다.']);
        }
        
        $insertMission = [
            'parent_id' => $parent->parent_id
            ,'child_id' => $request->child_id
            // ,'child_id' => $child->child_id
            ,'title' => $request->title
            ,'category' => $request->category
            ,'content' => $request->content
            ,'amount' => $request->amount
            ,'start_at' => $request->start_at
            ,'end_at' => $request->end_at
        ];
        // return response()->json($insertMission, 200);
        // exit;

        $missionDetail = Mission::create($insertMission);

        $responseData = [
            'success' => true
            ,'msg' => '미션 등록 성공'
            ,'missionDetail' => $missionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // **************부모 미션 삭제 페이지 **************
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
    // **************부모 미션 수정 페이지 **************
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

        // return response()->json($updateMission, 200);
        // exit;

        $updateMission->save();

        $responseData = [
            'success' => true
            ,'msg' => '미션 수정 성공'
            ,'updateMission' => $updateMission
        ];
        return response()->json($responseData, 200);
    }
}
