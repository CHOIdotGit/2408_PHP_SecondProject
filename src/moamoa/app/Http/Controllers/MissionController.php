<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class MissionController extends Controller
{
    // ************************************************
    // ****************부모 미션 리스트 ****************
    // ************************************************
    public function index($id) {
        // 로그인 유저가 부모인지 확인
        $parent = Auth::guard('parents')->user();

        $missionList = Mission::select('missions.mission_id', 'missions.child_id', 'missions.status', 'missions.category', 'missions.title', 'missions.amount', 'missions.start_at', 'missions.end_at')
                                    ->with('child')
                                    ->where('missions.parent_id', $parent->parent_id)
                                    ->where('missions.child_id', $id)
                                    ->orderBy('missions.status')
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
    // ************부모 선택된 미션만 삭제 **************
    // ************************************************
    public function checkedDestroy(Request $request) {

        $checkedMissionId = $request->missionIds;
        $deleteCheckedMission = Mission::destroy($checkedMissionId);


        $responseData = [
            'success' => true
            ,'msg' => '선택된 미션 삭제 성공'
            ,'checkedMissionId' => $checkedMissionId
            ,'삭제된 미션 갯수' => $deleteCheckedMission
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

    /**
     * 미션 승인
     * 
     * @param Request $request
     * @return JSON $responseData
     */
    public function approvalMission(Request $request) {
        try {
            DB::beginTransaction();

            $missions = Mission::whereIn('mission_id', $request->mission_ids)->where('status', 0)->get();
            if(count($missions) > 0) {
                foreach($missions as $mission) {
                    $transactionData = new Transaction();
                    $transactionData->parent_id = $mission->parent_id;
                    $transactionData->child_id = $mission->child_id;
                    $transactionData->category = '3';
                    $transactionData->transaction_code = '0';
                    $transactionData->title = $mission->title;
                    $transactionData->amount = $mission->amount;
                    $transactionData->transaction_date = now()->format('Y-m-d');
    
                    $transactionData->save();
                }
    
                Mission::whereIn('mission_id', $request->mission_ids)
                    ->where('status', 0)
                    ->update(['status' => 2]);
            }

            DB::commit();

            return response()->json([
                'success' => true
                ,'msg' => '미션 승인 업데이트 성공'
            ], 200);
        }catch(Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false
                ,'error' => '미션 승인 업데이트 실패'
            ], 401);
        }

    }
}
