<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\ParentModel;
use Illuminate\Http\Request;

class ChildInfoController extends Controller
{
    public function index() {
        // 예제
        $parent = ParentModel::find(1);
        $missionInfo = Mission::select('missions.child_id','missions.status', 'missions.category', 'missions.title', 'missions.amount', 'missions.start_at', 'missions.end_at')
                                ->with('child')
                                ->where([
                                    ['missions.parent_id', $parent->parent_id]
                                    , ['missions.child_id', 1]
                                ])
                                ->latest()
                                ->paginate(5);

        $responseData = [
            'success' => true
            ,'msg' => '미션리스트 획득 성공' 
            ,'missionInfo' => $missionInfo
        ];
        return response()->json($responseData, 200);
    }
}
