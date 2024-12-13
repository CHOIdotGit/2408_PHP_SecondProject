<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;

class ParentBoardController extends Controller
{
    // 1. 로그인 한 부모의 자식 리스트 불러오기
	// 지금은 parents_id = 1로 고정
    public function index() {
        // $parentsBoard = Mission::Orderby('children.name', 'children.nick_name');
        $parentsBoard = Mission::Orderby('created_at', 'DESC')->paginate(3);
        $responseData = [
            'success' => true
            ,'msg' => '자녀 불러오기 성공'
            ,'data' => $parentsBoard->toArray()
        ];
        return response()->json($responseData ,200);
    }
}
