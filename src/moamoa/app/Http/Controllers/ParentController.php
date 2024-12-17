<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\Child;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Request;

class ParentController extends Controller
{
    public function index() {
        // 예제
        $parent = ParentModel::find(1);
        // $missionList = Mission::select('missions.child_id')
        //                         ->where('missions.parent_id', $parent->parent_id)
        //                         ->with(['child', 'transactions' => function($query) {
        //                             $query->orderBy('created_at', 'DESC')->take(3);
        //                         }])
        //                         ->groupBy('missions.child_id')
        //                         ->orderBy('missions.status', 'ASC')
        //                         ->paginate(3);
        $missionList = Child::select('children.child_id', 'children.name', 'children.nick_name', 'children.profile')
                                ->where('children.parent_id', $parent->parent_id)
                                ->whereBetween('child_id', [1, 3])
                                ->with(['missions', 'transactions'])
                                ->get();

        
        

        // $pendingMissions = Mission::select('title') // title만 선택
        //                     ->where('parent_id', $parent->parent_id)
        //                     ->where('status', 1) // status가 1인 미션
        //                     ->orderBy('created_at', 'DESC')
        //                     ->paginate(3);

        // 결과가 없으면 메시지 설정
        // $pendingMessage = $pendingMissions->isEmpty() ? '대기중인 미션이 없습니다.' : null;
        
        $responseData = [
            'success' => true
            ,'msg' => '미션리스트 획득 성공'
            ,'missionList' => $missionList
            // ,'pendingMissions' => $pendingMissions   
            // ,'pendingMessage' => $pendingMessage   
        ];
        return response()->json($responseData, 200);
    }

    // public function getMissionTitle() {
    //     // status가 1인 미션
    //     $missionTile = Mission::where('status', 1)->get(['title']);

    //     // 미션 제목을 배열로 반환
    //     return response()->json($missionTile);
    // }

    // ************************************************
    // **********부모 미션 상세 페이지 불러오기**********
    // ************************************************
    public function show(Request $request) {
        // $parent = ParentModel::find(1);
        $MissionDetail = Mission::select('missions.title', 'missions.content', 'missions.amount', 'missions.start_at', 'missions.end_at')
                                ->where('missions.parent_id', 1)
                                ->first();

        $responseData = [
            'sucesse' => true
            ,'msg' => '자식 미션 상세 불러오기 성공'
            ,'missionDetail' => $MissionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }

    // ************************************************
    // **************부모 미션 작성 페이지 **************
    // ************************************************
    public function store(Request $request) {
        $insertMission = $request->only(['title', 'start_at', 'end_at', 'category', 'content']);

        $MissionDetail = Mission::create($insertMission);

        $responseData = [
            'sucesse' => true
            ,'msg' => '미션 등록 성공'
            ,'createMission' => $MissionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }
}
