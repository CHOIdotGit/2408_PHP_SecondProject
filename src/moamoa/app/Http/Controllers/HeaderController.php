<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
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
        $childNameList = Child::select('name', 'profile','created_at', 'child_id')
                                ->where('parent_id', $parent->parent_id)
                                // ->groupBy('parent_id')
                                ->orderBy('created_at')
                                ->get();

        // 자녀 없을 때 - 유효성 검사
        if($childNameList -> isEmpty()) {
            $responseData = [
                'success' => true
                ,'msg' => '자녀가 없을때'
                ,'childNameList' => $childNameList
            ];
            return response()->json($responseData, 200);
        }

        $responseData = [
            'success' => true
            ,'msg' => '자녀 이름 목록 출력'
            ,'childNameList' => $childNameList
        ];
        return response()->json($responseData, 200);
                                
    }

    public function bellList() {
        // ************************************************
        // 헤더 알람 : 자녀가 미션 등록하면 알람 목록에 내역 출력
        // alarm = 0 일 때 체크 안 된 상태
        // alarm = 1 일 때 체크 한 상태
        // ************************************************ 
        $parent = Auth::guard('parents')->user();
        // $bellContent = Child::select('children.child_id', 'children.name', 'children.profile')
        //                     ->where('children.parent_id', $parent->parent_id)
        //                     ->with([
        //                         'missions' => function ($query) {
        //                             $query->where('alarm', 0) // 미션 알람이 0인 조건
        //                                     ->orderBy('created_at', 'DESC');
        //                         }
        //                     ])
        //                     ->get();
        $bellContent = Mission::select('missions.mission_id','missions.title', 'missions.created_at', 'missions.child_id')
                                ->where('missions.parent_id', $parent->parent_id)    
                                ->where('missions.status', 0)
                                ->where('missions.alarm', 0)
                                ->orderBy('created_at', 'DESC')
                                ->with([
                                    'child' => function ($query) {
                                        $query->select('children.child_id', 'children.name', 'children.profile');
                                    }
                                ])
                                ->get();

        $responseData = [
            'success' => true
            ,'bellContent' => $bellContent
        ];
        return response()->json($responseData, 200);
    }

    public function childBellList() {
        // ************************************************
        // 헤더 알람 : 부모가 미션을 승인하면 알람 목록에 출력
        // status = 2 일때 미션 완료
        // alarm = 0 일 때 체크 안 된 상태
        // alarm = 1 일 때 체크 한 상태
        // 추후 부모랑 합칠 것
        // ************************************************ 
        
        // 자녀가 로그인 했을 떄
        $child = Auth::guard('children')->user();
        $childBellContent = Mission::select('missions.mission_id','missions.title', 'missions.created_at', 'missions.child_id')
                                    ->where('missions.child_id', $child->child_id)   
                                    ->where('missions.status', 2)
                                    ->where('missions.alarm', 0)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
        $responseData = [
            'success' => true
            ,'childBellContent' => $childBellContent
        ];
        return response()->json($responseData, 200);
    }

    public function update($mission_id) {
        // ************************************************
        // 헤더 알람 : 체크누르면 alarm을 1로 변경
        // alarm = 0 일 때 체크 안 된 상태
        // alarm = 1 일 때 체크 한 상태
        // ************************************************ 
        $parent = Auth::guard('parents')->user();
        $bellCheck = Mission::where('parent_id', $parent->parent_id)
                            ->where('mission_id', $mission_id)
                            ->where('alarm', 0)
                            ->update(['alarm' => 1]);

        $responseData = [
            'success' => true
            ,'bellCheck' => $bellCheck
        ];
        return response()->json($responseData, 200);
    }
    // public function update(Request $request) {
    //     // ************************************************
    //     // 헤더 알람 : 체크누르면 alarm을 1로 변경
    //     // alarm = 0 일 때 체크 안 된 상태
    //     // alarm = 1 일 때 체크 한 상태
    //     // ************************************************ 
    //     $parent = Auth::guard('parents')->user();
    //     $updateAlarm = Mission::find($request->mission_id);
    //     $updateAlarm->alarm = $request->alarm;

    // }

    public function childInfo() {
        // ************************************************
        // *********로그인한 자녀의 프로필 정보 출력**********
        // ************************************************ 
        $child = Auth::guard('children')->user();

        $childInfo = Child::select('children.name', 'children.profile')
                            ->where('children.child_id', $child->child_id)
                            ->first();
        
        $responseData = [
            'success' => true
            ,'msg' => '로그인한 자녀 프로필 정보 불러오기 성공'
            ,'childInfo' => $childInfo->toArray()
        ];
        return response()->json($responseData, 200);
    }

}
