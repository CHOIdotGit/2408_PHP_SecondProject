<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index() {
        $missionList = Mission::orderBy('created_at', 'DESC')->paginate(3);
        
        $responseData = [
            'success' => true
            ,'msg' => '미션 획득 성공'
            ,'missionList' => $missionList->toArray()
        ];

        return response()->json($responseData, 200);
    }

    // public function getMissionTitle() {
    //     // status가 1인 미션
    //     $missionTile = Mission::where('status', 1)->get(['title']);

    //     // 미션 제목을 배열로 반환
    //     return response()->json($missionTile);
    // }
}
