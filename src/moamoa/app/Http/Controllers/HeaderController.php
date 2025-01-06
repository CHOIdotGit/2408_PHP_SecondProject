<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeaderController extends Controller
{
public function index() {
    // ************************************************
    // ************헤더 자녀 이름 목록 출력**************
    // ************************************************ 
    //Auth 가 로그인한 유저의 정보를 담고 있고, guard가 어떤 테이블인지(children, parents )
    $parent = Auth::guard('parents')->user();
    // $parentId = ParentModel::where('parent_id', $parent->parent_id)->first();
    $childNameList = Child::select('name', 'created_at', 'child_id')
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

public function bellList() {
        // ************************************************
    // *********헤더 미션/지출 알람 목록 출력************
    // ************************************************ 
    // $parent = Auth::guard('parents')->user();
    // $bellContent = Child::select('children.child_id', 'children.name', 'children.profile')
    //                     ->where('children.parent_id', $parent->parent_id)
    //                     ->with([
    //                         'missions' => function ($query) {
    //                             $query->where('alarm', 0) // 미션 알람이 0인 조건
    //                                     ->orderBy('created_at', 'DESC');
    //                         }
    //                     ])
    //                     ->get();

    // $responseData = [
    //     'success' => true
    //     ,'등록한 미션 알람' => $bellContent
    // ];
    // return response()->json($responseData, 200);
}

}
