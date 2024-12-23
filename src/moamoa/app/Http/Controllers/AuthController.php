<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParentRequest;
use App\Models\Child;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        ,'redirect_to' => 'parent/home'
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
   * 회원가입 정보 저장
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function storeUser(Request $request) {
    // 인서트 데이터 세팅
    $insertData = $request->only('account', 'name', 'tel', 'email');
    $insertData['password'] = Hash::make($request->password);

    // 사용자가 추가로 닉네임을 입력했다면
    if($request->nick_name) {
      $insertData['nick_name'] = $request->nick_name;
    }

    // 사용자가 추가로 프로필 사진을 올렸다면
    if($request->profile) {
      $insertData['profile'] = '/'.$request->file('profile')->store('profile');
    }else {
      // 프로필 사진이 없는 경우 랜덤 디폴트 이미지로 설정
    }

    // 부모는 발급한 가족코드가 있음
    if($request->family_code) {
      $insertData['family_code'] = $request->family_code;

      // 부모 레코드 생성
      try {
        $parent = $this->authRepository->createParent($insertData);
      }catch(Throwable $th) {
        return response()->json(['error' => '부모 레코드 생성 실패: ' . $th->getMessage()], 500);
      }

      // 리스폰스 데이터 작성
      $responseData = [
        'success' => true,
        'msg' => '부모 회원작성 성공',
        // 'data' => $parent->toArray()
      ];

      return response()->json($responseData, 200);
    }

    // 자녀는 연결할 부모ID를 가지고 있음
    if($request->parent_id) {
      $insertData['parent_id'] = $request->parent_id;

      // 자녀 레코드 생성
      try {
        $child = $this->authRepository->createChild($insertData);
      }catch(Throwable $th) {
        return response()->json(['error' => '자녀 레코드 생성 실패: ' . $th->getMessage()], 500);
      }

      // 리스폰스 데이터 작성
      $responseData = [
        'success' => true,
        'msg' => '자녀 회원작성 성공',
        // 'data' => $child->toArray()
      ];
      
      return response()->json($responseData, 200);
    }

    return response()->json(['error' => '예기치 못한 문제 발생'], 500);
  }

  /**
   * 부모 가족코드 발급
   * 
   * @return JSON family_code
   */
  public function parentRegistCode() {
    // 중복이 나지않게 반복 처리
    while(true) {
      // 랜덤코드를 만들 내부 함수 호출
      $family_code = $this->addFamilyCode();

      // 가족코드 중복 검사, 없을시 반복멈춤
      if(!($this->authRepository->findDuplicateFamilyCode($family_code))) {
        break;
      }
    }

    return response()->json(['family_code' => $family_code], 200);
  }

  /**
   * 가족코드 부모 검사
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function childRegistMatching(Request $request) {
    if($request->fam_code && (mb_strlen($request->fam_code) === 8)) {
      $parent = $this->authRepository->findParentByFamilyCode($request->fam_code);
      if($parent) {
        $responseData = [ 
          'success' => true,
          'msg' => '부모 매칭 성공',
          'parent' => $parent->toArray()
        ];

        return response()->json($responseData, 200);
      }
    }

    return response()->json([
      'success' => false,
      'msg' => '해당하는 코드는 존재하지 않는 코드입니다.',
    ], 200);
  }
  
  /**
   * 
   * axios 미사용 내부 호출용 함수
   * 
   */

  // 가족 코드 생성
  private function addFamilyCode() {
    // 영대문자와 숫자를 각각 정의
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    
    // 영문 4개, 숫자 4개를 섞어놓기 위한 배열 초기화
    $characters = [];
    
    // 중복제외 배열에 랜덤 영문 4개와
    for($i = 0; $i < 4; $i++) {
      $randomStr = $letters[rand(0, strlen($letters) - 1)];
      $characters[] = $randomStr;
      substr_replace($letters, mb_strpos($letters, $randomStr), 1);
    }
    
    // 중복제외 랜덤 숫자 4개 각각 담음
    for($i = 0; $i < 4; $i++) {
      $randomNum = $numbers[rand(0, strlen($numbers) - 1)];
      $characters[] = $randomNum;
      substr_replace($numbers, mb_strpos($numbers, $randomNum), 1);
    }
    
    // 이 각각 담긴 배열을 섞어서
    shuffle($characters);
    
    // 문자열로 변환해 8자 코드로 변환
    $randomString = implode('', $characters);
    
    return $randomString;
  }
}
