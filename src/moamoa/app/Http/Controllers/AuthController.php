<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
  
  /**
   * 로그인 처리
   * 
   * @params Request $request
   * 
   * @return JSON $responseData
   */
  public function login(Request $request) {
    // $user = ParentModel::find(1);
    
    // 유효성 검사
    // $validator = Validator::make(
    //   $request->only('account', 'password')
    //   ,[
    //     'account' => ['required', 'regex:^[a-z][a-z0-9]{4,10}$'] // 영어 소문자로 시작하는 영어 소문자 및 숫자로 이루어진 4~10글자
    //     ,'password' => ['required','between:6,20', 'regex:/^[a-zA-Z0-9!@]+$/'] // 영대문자숫자포함 6~20글자 
    //   ]
    // );

    // // 유효성 검사 실패시 처리
    // if($validator->fails()) {
    //   $responseData = [
    //     'success' => false
    //     ,'msg' => '유효성 검사 실패'
    //     ,'errors' => $validator->errors()
    //   ];

    //   return response()->json($responseData, 422);
    // }

    // 해당 아이디가 부모건지 체크
    $parent = ParentModel::where('account', $request->account)->first();
    if($parent && Hash::check($request->password, $parent->password)) {
      Auth::guard('parents')->login($parent);

      $responseData = [
        'success' => true
        ,'msg' => '부모 로그인 성공'
        ,'user' => $parent->toArray()
        ,'redirect_to' => 'parents/home'
      ];

      return response()->json($responseData, 200);
    }

    // 해당 아이디가 자녀건지 체크
    $child = Child::where('account', $request->account)->first();
    if($child && Hash::check($request->password, $child->password)) {
      Auth::guard('children')->login($child);

      $responseData = [
        'success' => true
        ,'msg' => '자녀 로그인 성공'
        ,'user' => $child->toArray()
        ,'redirect_to' => 'children/home'
      ];

      return response()->json($responseData, 200);
    }
    
    $responseData = [
      'success' => false
      ,'msg' => '계정이 없거나 일치하는 아이디 혹은 비밀번호가 맞지 않습니다.'
    ];

    return response()->json($responseData, 401);
  }

  /**
   * 로그아웃 처리
   * 
   * @return JSON $responseData
   */
  public function logout() { 

    Auth::logout(); // 로그아웃 처리
    Session::invalidate(); // 기존 세션 제거후 새 새션 발급
    Session::regenerateToken(); // CSRF토큰 재발급

    $responseData = [
      'success' => true
      ,'msg' => '로그아웃 성공'
      ,'csrf_token' => csrf_token()
    ];

    return response()->json($responseData, 200);
  }
}
