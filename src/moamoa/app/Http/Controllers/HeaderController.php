<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
public function index() {
    // 헤더 메뉴에 알림 드랍 메뉴 데이터
    // $bellContent = Child::select('children.child_id','children.name')
    //                 ->where('children.parent_id', $parent->parent_id)
    //                 ->with(['missions', 'transactions'])
    //                 // ->orderBy('missions.created_at', 'DESC')
    //                 ->limit(3)
    //                 ->get();

    // $responseData = [
    //     'success' => true
    //     ,'msg' => '미션리스트 획득 성공'
    //     ,'bellContent' => $bellContent
    //     // ,'pendingMissions' => $pendingMissions   
    //     // ,'pendingMessage' => $pendingMessage   
    // ];
    // return response()->json($responseData, 200);

    // ************************************************
    // ************헤더 자녀 이름 목록 출력**************
    // ************************************************ 
    $childNameList = Child::select('name')
                            ->where('parent_id', 1)
                            ->get();

    $responseData = [
        'success' => true
        ,'msg' => '자녀 이름 목록 출력'
        ,'childNameList' => $childNameList
    ];
    return response()->json($responseData, 200);
                            
}

}