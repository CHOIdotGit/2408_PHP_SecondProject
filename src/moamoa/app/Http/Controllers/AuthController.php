<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\FamilyCodeRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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
   * 로그인 세션 만료 체크
   * 
   * @return Boolean $result
   */
  public function checkSession() {
    $parent = Auth::guard('parents')->check();
    $child = Auth::guard('children')->check();
    $result = ($parent || $child) ? true : false;

    return response()->json(['isExpired' => $result], 200);
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
          ? '/'.$request->file('profile')->store('user-img') 
        // 없으면 랜덤한 디폴트 이미지 경로를 넣음 
          : '/user-img/default'.rand(0,9).'.webp'
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
            // Auth::guard('children')->login($child);

            // 리스폰스 데이터 작성
            $responseData = [
              'success' => true,
              'msg' => '자녀 회원작성 성공',
              // 'child' => $child,
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
            // Auth::guard('parents')->login($parent);

            // 코드뷰 페이지에서 필요한 정보만 세팅
            $parentInfo = [
              'name' => $parent->name,
              'family_code' => $parent->family_code,
            ];

            // 리스폰스 데이터 작성
            $responseData = [
              'success' => true,
              'msg' => '부모 회원작성 성공',
              'parent' => $parentInfo,
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
  public function childRegistMatching(AuthRequest $request) {
    // 가족코드가 입력됫고 제대로 8글자가 왔다면
    if($request->fam_code && (mb_strlen($request->fam_code) === 8)) {
      $parent = $this->authRepository->findParentByFamilyCode($request->fam_code);

      if($parent) {
        $responseData = [ 
          'success' => true,
          'msg' => '부모 매칭 성공',
          'parent' => $parent,
        ];

        return response()->json($responseData, 200);
      }
    }

    return response()->json([
      'success' => false,
      'error' => [ 'fam_code' => ['0' => '존재하지 않는 코드입니다.']],
    ], 422);
  }

  /**
   * 부모 정보 조회
   * 
   * @return JSON $responseData
   */
  public function parentInfo() {
    $parent = Auth::guard('parents')->user();

    // 부모 정보 조회 + 자녀들 레코드
    $parentInfo = $this->authRepository->parentInfoWithChildren($parent->parent_id);

    return response()->json([
      'success' => true,
      'parent' => $parentInfo,
    ]);
  }

  /**
   * 자녀 정보 조회
   * 
   * @return JSON $responseData
   */
  public function childInfo() {
    $child = Auth::guard('children')->user();

    // 자녀 정보 조회 + 부모 레코드
    $childInfo = $this->authRepository->childInfoWithParent($child->child_id);

    return response()->json([
      'success' => true,
      'child' => $childInfo,
    ]);
  }

  /**
   * 회원 정보 수정
   * 
   * @param AuthRequest $request
   * 
   * @return JSON $responseData
   */
  public function modifyUser(AuthRequest $request) {
    if(empty($request->all()) || // 요청값이 아무것도 없거나
      $request->missing(['password', 'password_chk', 'name', 'tel', 'email']) // 필수값중에 하나라도 없으면
    ) { 
      return response()->json([
        'success' => false,
        'error' => '요청값이 없거나 필수적으로 들어가야할 정보가 없습니다.',
      ], 401);
    }else {
      try{
        // 로그인한 유저 정보를 세팅
        if(Auth::guard('parents')->check()) {
          $user = Auth::guard('parents')->user();
          $colName = 'parent_id';
        }else {
          $user = Auth::guard('children')->user();
          $colName = 'child_id';
        }
  
        // 업데이트 데이터 초기화
        $updateData = [];
  
        // 루프를 돌려 값이 바뀐것만 세팅
        foreach(['name', 'email', 'tel'] as $field) {
          if($request->$field !== $user->$field) {
            $updateData[$field] = $request->$field;
          }
        }
  
        // 닉네임이 들어왔고 들어온 닉네임이 기존에 있는 닉네임과 같지 않다면 세팅
        if($request->nick_name && $request->nick_name !== $user->nick_name) {
          $updateData['nick_name'] = $request->nick_name;
        }
        
        // 이미지가 새로 들어왔다면
        if($request->profile) {
          // 이미지가 디폴트 이미지가 아닌 경우에만 삭제
          if(!str_contains($user->profile, 'default')) {
            Storage::delete(ltrim($user->profile, '/')); // 앞의 '/' 제거하고 삭제
          }
  
          // 이미지를 새로 업로드
          $updateData['profile'] = '/'.$request->file('profile')->store('user-img');
        }
  
        // 변경된 값이 하나라도 존재 한다면 업데이트 실행
        // 없다면 그냥 넘어감
        if(!empty($updateData)) {
          $userInfo = [
            'column_name' => $colName,
            'column_id' => $user->$colName
          ];

          $this->authRepository->updateUser($userInfo, $updateData);
        }

        return response()->json([
          'success' => true,
          'msg' => '회원 정보 수정 성공',
        ], 200);

      }catch(Throwable $th) {
        return response()->json([
          'success' => false,
          'error' => '회원 정보 수정 실패: ' . $th->getMessage(),
        ], 500);
      }
    }
  }
  
  /**
   * 회원 탈퇴 처리
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function removeUser(PasswordRequest $request) {
    if(empty($request->all()) || $request->missing(['password'])) { 
      return response()->json([
        'success' => false,
        'error' => '요청값이 없거나 필수적으로 들어가야할 정보가 없습니다.',
      ], 401);
    }else {
      try {
        // 로그인한 유저 정보를 세팅
        if(Auth::guard('parents')->check()) {
          $user = Auth::guard('parents')->user();
          $colName = 'parent_id';
        }else {
          $user = Auth::guard('children')->user();
          $colName = 'child_id';
        }
        
        $userInfo = [
          'column_name' => $colName,
          'column_id' => $user->$colName
        ];

        // 회원 탈퇴 실행
        $this->authRepository->removeUser($userInfo);

        $responseData = [
          'success' => true,
          'msg' => '회원 탈퇴 성공'
        ];

        return response()->json($responseData, 200);

      }catch(Throwable $th) {
        return response()->json([
          'success' => false,
          'error' => '회원 탈퇴 실패: ' . $th->getMessage(),
        ], 500);
      }
    }
  }

  /**
   * 회원 비밀번호 변경 처리
   * 
   * @param PasswordRequest $request
   * 
   * @return JSON $responseData
   */
  public function changePassword(PasswordRequest $request) {
    if(empty($request->all()) || $request->missing(['password', 'newPassword', 'newPasswordChk'])) { 
      return response()->json([
        'success' => false,
        'error' => '요청값이 없거나 필수적으로 들어가야할 정보가 없습니다.',
      ], 401);
    }else {
      try {
        // 로그인한 유저 정보를 세팅
        if(Auth::guard('parents')->check()) {
          $user = Auth::guard('parents')->user();
          $colName = 'parent_id';
        }else {
          $user = Auth::guard('children')->user();
          $colName = 'child_id';
        }
        
        $userInfo = [
          'column_name' => $colName,
          'column_id' => $user->$colName
        ];

        $updateData = [
          'password' => Hash::make($request->newPassword)
        ];

        // 비밀번호 업데이트 실행
        $this->authRepository->updateUser($userInfo, $updateData);

        $responseData = [
          'success' => true,
          'msg' => '비밀번호 변경 성공'
        ];

        return response()->json($responseData, 200);
        
      }catch(Throwable $th) {
        return response()->json([
          'success' => false,
          'error' => '비밀번호 변경 실패: ' . $th->getMessage(),
        ], $th->getCode());
      }
    }
  }

  /**
   * 자녀 연결 레코드 처리
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function childManyInfo() {
    $child = Auth::guard('children')->user();

    $childInfo = $this->authRepository->childInfoWithMissionsAndTransactions($child->child_id);

    return response()->json([
      'success' => true,
      'child' => $childInfo,
    ]);
  }
}
