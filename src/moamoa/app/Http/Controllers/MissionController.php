<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MissionController extends Controller
{
    public function index() {
        // 예제
        $parent = Auth::guard('parents')->user();
        $missionList = Child::select('children.child_id', 'children.name', 'children.nick_name', 'children.profile')
                                ->where('children.parent_id', $parent->parent_id)
                                ->get();


        foreach($missionList as $child) {
            $child->setRelation('missions', $child->missions()->latest()->where('missions.status', 1)->limit(3)->get());
            $child->setRelation('transactions', $child->transactions()->latest()->limit(3)->get());
        }
        
        $missionInfo = Mission::select('missions.child_id','missions.status', 'missions.category', 'missions.title', 'missions.amount', 'missions.start_at', 'missions.end_at')
                                ->with('child')
                                ->where([
                                    ['missions.parent_id', $parent->parent_id]
                                    , ['missions.child_id', 1]
                                ])
                                ->latest()
                                ->paginate(15);
       
        $responseData = [
            'success' => true
            ,'msg' => '미션리스트 획득 성공'
            ,'missionList' => $missionList
            ,'missionInfo' => $missionInfo
        ];
        return response()->json($responseData, 200);
    }

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
        $insertMission = $request->only(['title', 'start_at', 'end_at', 'category', 'content','amount']);
        $insertMission['parent_id'] = 1; //일단 부모 id=1 고정
        $insertMission['child_id'] = 1;  //일단 자녀 id=1고정
        $MissionDetail = Mission::create($insertMission);
                                    


        $responseData = [
            'sucesse' => true
            ,'msg' => '미션 등록 성공'
            ,'createMission' => $MissionDetail->toArray()
        ];
        return response()->json($responseData, 200);
    }
}
