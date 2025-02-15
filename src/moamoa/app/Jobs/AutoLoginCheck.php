<?php

namespace App\Jobs;

use App\Models\Child;
use App\Models\Point;
use Illuminate\Support\Facades\Auth;

class AutoLoginCheck extends MyJob {
    // ***********************************
    // 로그인 한번 하면 포인트 지급
    // 포인트 = 5
    // point_code = 0 (출석체크)
    // ***********************************
    public function process()
    {
        
        // 로그인 유저 가 자녀인지 확인
        $child = Auth::guard('children')->user();

        if(!$child) {
            return;
        }

        $today = now()->format('Y-m-d');

        // 로그인 한 날짜 확인
        $lastLoginDate = Child::where('child_id', $child->child_id)
                                ->value('login_at'); 
                                // 라라벨 : 전체 행이 필요하지 않다면 value에서 하나의 값만 추철
        if($lastLoginDate === $today ) {
            return response()
                    ->json(['msg' => '이미 포인트가 지급되었습니다.']);
    
        }


        // 로그인 시간 업데이트
        $lastLoginDate = Child::where('child_id', $child->child_id)
                            ->update(['login_at' => $today]);

        // 로그인 하면 하루에 한번 포인트 지급
        $point = [
            'child_id' => $child->child_id
            ,'point' => 5
            ,'point_code' => "0"
            ,'payment_at' => $today
        ];
    


        // $loginCheck = Child::select('login_at')
        //                     ->get();


    }
}
