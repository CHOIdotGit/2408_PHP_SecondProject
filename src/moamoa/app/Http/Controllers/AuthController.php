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
use Throwable;

class AuthController extends Controller {
  
  /**
   * 로그인 처리
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function login(Request $request) {
    // $user = ParentModel::find(1);
    
    // 유효성 검사
    // $validator = Validator::make(
    //   $request->only('account', 'password')
    //   ,[
    //     'account' => ['required', 'regex:^[a-z][a-z0-9]{6,20}$'] // 영어 소문자로 시작하는 영어 소문자 및 숫자로 이루어진 4~20글자
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
        ,'redirect_to' => 'child/home'
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
      // ,'csrf_token' => csrf_token()
    ];

    return response()->json($responseData, 200);
  }

  /**
   * CSRF 토큰 발행
   * 
   * @return JSON CSRF_TOKEN
   */
  public function settingCsrfToken() {
    return response()->json(['csrf_token' => csrf_token()], 200);
  }

  /**
   * 회원가입 아이디 중복 체크
   * 
   * @param String $account
   * 
   * @return JSON $responseData
   */
  public function chkAccount($account) {
    // 공백이나 특수문자가 들어가 있는지
    if(preg_match('/[\s\W]/', $account)) { 
      return response()->json([
        'color' => 'color-red' // 빨간색 글자 CSS
        ,'msg' => '공백 혹은 특수문자가 포함되어 있습니다.'
      ]);
    }

    // 영문 및 숫자(필수x)로 이루어진 4~20글자가 아닌지
    if(!preg_match('/^[a-z][a-z0-9]{6,20}$/', $account)) {
      return response()->json([
        'color' => 'color-red' // 빨간색 글자 CSS
        ,'msg' => '　영문 및 숫자조합 6 ~ 20글자만　입력가능 합니다.'
      ]);
    }

    // 아이디 중복 체크
    if($this->authRepository->findDuplicateAccount($account)) {
      // 중복된 아이디 존재함
      return response()->json([
        'color' => 'color-red' // 빨간색 글자 CSS
        ,'msg' => '동일한 아이디가 이미 존재합니다.'
      ]);
    }else {
      // 중복 아이디 없음
      return response()->json([
        'color' => 'color-green' // 초록색 글자 CSS
        ,'msg' => '사용가능한 아이디 입니다.'
      ]);
    }
  }

  /**
   * 회원가입 정보 임시 저장
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function saveRegistInfo(Request $request) {
    // TODO: 유효성 검사 수행헤야함

    return response()->json(['success' => true], 200);
  }

  /**
   * 랜덤 가족코드 생성
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */

  public function registCode(Request $request) {
    // 대문자와 숫자를 포함한 6자리 랜덤 문자열 생성
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = substr(str_shuffle($characters), 0, 6);
    
    // 부모테이블 가족코드를 조회하여 중복확인

    // 전화번호 끝 두자리 자르기
    // $phone = substr($request->phone, -2);

    return response()->json(['randomString' => $randomString]);
  }
}