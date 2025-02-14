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
                                    ->with('child')
                                    // ->whereNull('missions.deleted_at')
                                    ->orderBy('missions.status')
                                    ->latest()
                                    ->paginate(20);
        
        $responseData = [
            'success' => true
            ,'msg' => '미션리스트 획득 성공'
            ,'childMissionList' => $childMissionList
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // ********************필터 검색 *******************
    // ************************************************
    public function search(Request $request) {
        // 자녀 확인
        $child = Auth::guard('children')->user();

        $category = $request->category;
        $startDate = $request->start_at;
        $endDate = $request->end_at;
        $keyword = $request->keyword;
        Log::debug('request', $request->all());
        $filters = Mission::with('child')
        ->where([
            ['missions.child_id', $child->child_id]
        ])
        ->when($category !== '', function($query) use ($category) {
            return $query->where('missions.category', $category);
        })
        ->when($startDate, function($query) use ($startDate) {
            return $query->whereDate('missions.start_at', '=', $startDate);
        })
        ->when($endDate, function($query) use ($endDate) {
            return $query->whereDate('missions.end_at', '=', $endDate);
        })
        ->when($keyword !== '', function($query) use ($keyword) {
            return $query->where('missions.title', 'like', '%' . $keyword . '%');
        })
        ->orderBy('missions.end_at' ,'ASC')
        ->paginate(20);

        $responseData = [
            'success' => true
            ,'msg' => '필터 성공'
            ,'filters' => $filters
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
    // ************자녀 선택된 미션만 삭제 **************
    // ************************************************
    public function checkedDestroy(Request $request) {
        // exit;
        $checkedMissionId = $request->missionIds;
        // return response()->json(['checkedMissionId' => $checkedMissionId], 200);
        // return $checkedMissionId;
        // exit;
        $deleteCheckedMission = Mission::destroy($checkedMissionId);

        //배열로 받아오는지 아닌지 확인
        // if (!$checkedMissionId || !is_array($checkedMissionId)) {
        //     return response()->json([
        //         'success' => false,
        //         'msg' => '유효하지 않은 요청입니다.'
        //     ], 400); // HTTP 400 에러 반환
        // }

        $responseData = [
            'success' => true
            ,'msg' => '선택된 미션 삭제 성공'
            ,'checkedMissionId' => $checkedMissionId
            ,'삭제된 미션 갯수' => $deleteCheckedMission
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
