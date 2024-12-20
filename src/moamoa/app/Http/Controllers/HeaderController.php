<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    //Auth 가 로그인한 유저의 정보를 담고 있고, guard가 어떤 테이블인지(children, parents )
    $parent = Auth::guard('parents')->user();
    // $parentId = ParentModel::where('parent_id', $parent->parent_id)->first();
    $childNameList = Child::select('name', 'created_at')
                            ->where('parent_id', $parent->parent_id)
                            // ->groupBy('parent_id')
                            ->orderBy('created_at')
                            ->get();

    $responseData = [
        'success' => true
        ,'msg' => '자녀 이름 목록 출력'
        ,'childNameList' => $childNameList
    ];
    return response()->json($responseData, 200);
                            
}

}
