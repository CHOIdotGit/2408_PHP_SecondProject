<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Throwable;

class AuthController extends Controller {
  
  /**
   * 로그인 처리
   * 
   * @param AuthRequest $request // 커스텀 유효성 검사 리퀘스트
   * 
   * @return JSON $responseData
   */
  public function login(AuthRequest $request) {
    // 입력값이 둘중 하나라도 없을경우 예외 반환
    if($request->missing(['account', 'password'])) {
      return response()->json([
        'success' => false
        ,'error' => '입력된 계정 정보가 없습니다.'
      ], 401);
    }else {
      // 로그인 정보를 세팅
      $loginInfo = $request->only('account', 'password');
  
      // 각 테이블에 정보 조회
      $parent = $this->authRepository->findParentByAccount($loginInfo['account']);
      $child = $this->authRepository->findChildByAccount($loginInfo['account']);
  
      // 부모 혹은 자식 둘다 맞는게 없을경우
      if(!($parent && Hash::check($loginInfo['password'], $parent->password))
      && !($child && Hash::check($loginInfo['password'], $child->password))
      ) {
        // 둘중 일치하는게 없다는 에러 메세지를 반환
        return response()->json([
          'success' => false
          ,'error' => '없는 계정이거나 아이디 혹은 비밀번호가 맞지 않습니다.'
        ], 401);
      }else {
  
        if($parent) { // 부모면 부모로그인
          Auth::guard('parents')->login($parent);
    
          $responseData = [
            'success' => true
            ,'msg' => '부모 로그인 성공'
            ,'user' => $parent
            ,'redirect_to' => 'parent/home'
          ];
        }else { //아니면 자녀 로그인
          Auth::guard('children')->login($child);
    
          $responseData = [
            'success' => true
            ,'msg' => '자녀 로그인 성공'
            ,'user' => $child
            ,'redirect_to' => 'child/home'
          ];
        }
  
        // 성공된 데이터를 반환
        return response()->json($responseData, 200);
      }
    }
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
      return response()->json(['msg' => '공백 혹은 특수문자가 포함되어 있습니다.']);
    }

    // 영문 및 숫자로 이루어진 6~18글자가 아닌지
    elseif(!preg_match('/^[a-zA-Z][a-zA-Z0-9]{5,17}$/', $account)) {
      return response()->json(['msg' => '　영문 및 숫자조합 6 ~ 18글자만　입력가능 합니다.']);
    }

    // 아이디 중복 체크
    if($this->authRepository->findDuplicateAccount($account)) {
      // 중복된 아이디가 존재함
      return response()->json(['msg' => '동일한 아이디가 이미 존재합니다.']);
    }else {
      // 중복 아이디 없음
      return response()->json([
        'color' => true // 초록색 글자 여부
        ,'msg' => '사용가능한 아이디 입니다.'
      ]);
    }
  }

  /**
   * 회원가입 정보 저장
   * 
   * @param AuthRequest $request // 커스텀 유효성 검사 리퀘스트
   * 
   * @return JSON $responseData
   */
  public function storeUser(AuthRequest $request) {
    if(empty($request->all()) || // 요청값이 아무것도 없거나
      $request->missing(['account', 'password', 'password_chk', 'name', 'tel', 'email']) // 필수값중에 하나라도 없으면
    ) { 
      return response()->json([
        'success' => false,
        'error' => '요청값이 없거나 필수적으로 들어가야할 정보가 없습니다.',
      ], 401);
    }else{
      try {
        // 인서트 데이터 세팅
        $insertData = $request->only('account', 'name', 'tel', 'email');
        $insertData['password'] = Hash::make($request->password);

        // 사용자가 추가로 프로필 사진을 올렸다면
        $insertData['profile'] = $request->profile 
        // 해당 파일을 만들어서 경로를 넣고
          ? '/'.$request->file('profile')->store('profile') 
        // 없으면 랜덤한 디폴트 이미지 경로를 넣음 
          : '/profile/default'.rand(0,9).'.webp'
        ;

        // 사용자가 추가로 닉네임을 입력했다면 데이터 세팅
        if($request->nick_name) {
          $insertData['nick_name'] = $request->nick_name;
        }
        
        // 부모ID가 들어왔다면 자녀
        if($request->parent_id) {
          // 자녀 레코드 생성
          try {
            $insertData['parent_id'] = $request->parent_id;
            $child = $this->authRepository->createChild($insertData);

            // 생성한 레코드로 로그인
            Auth::guard('children')->login($child);

            // 리스폰스 데이터 작성
            $responseData = [
              'success' => true,
              'msg' => '자녀 회원작성 성공',
              // 'data' => $child,
              'redirect_to' => '/regist/complete'
            ];
            return response()->json($responseData, 200);
          }catch(Throwable $th) {
            return response()->json([
              'success' => false,
              'error' => '자녀 레코드 생성 실패: ' . $th->getMessage(),
            ], 500);
          }
        }else{ // 아니면 부모
          try {
            // 부모 레코드 생성
            $parent = $this->authRepository->createParent($insertData);

            // 생성한 레코드로 로그인
            Auth::guard('parents')->login($parent);

            // 리스폰스 데이터 작성
            $responseData = [
              'success' => true,
              'msg' => '부모 회원작성 성공',
              'parent' => $parent,
              'redirect_to' => '/regist/parent/code',
            ];
            return response()->json($responseData, 200);
          }catch(Throwable $th) {
            return response()->json([
              'success' => false,
              'error' => '부모 레코드 생성 실패: ' . $th->getMessage(),
            ], 500);
          }
        }
      }catch(Throwable $th){
        return response()->json([
          'success' => false,
          'error' => '회원작성 실패: ' . $th->getMessage(),
        ], 500);
      }
    }
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
    ], 401);
  }

  public function loginUser() {
    if(Auth::guard('parents')->check()) {
      $user = Auth::guard('parents')->user();
    }elseif(Auth::guard('children')->check()) {
      $user = Auth::guard('children')->user();
    }

    $responseData = [
      'success' => true,
      'user' => $user,
    ];

    return response()->json($responseData, 200);
  }
}
