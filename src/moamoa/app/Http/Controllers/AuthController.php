<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendEmail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller {
  
  /**
   * 로그인 처리
   * 
   * @param AuthenticationRequest $request // 커스텀 유효성 검사 리퀘스트
   * 
   * @return JSON $responseData
   */
  public function login(AuthenticationRequest $request) {
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
      $notApplyChild = $this->authRepository->findNotApplyChildByAccount($loginInfo['account']);
  

      // 미승인된 계정
      if($notApplyChild) {
        return response()->json([
          'success' => false
          ,'error' => '해당 계정은 미승인된 계정입니다. 보호자의 승인을 받아주세요.'
        ], 401);
      }

      // 부모 혹은 자식 둘다 맞는게 없을경우
      elseif(!($parent && Hash::check($loginInfo['password'], $parent->password))
          && !($child  && Hash::check($loginInfo['password'], $child->password))
      ) {
        // 둘중 일치하는게 없다는 에러 메세지를 반환
        return response()->json([
          'success' => false
          ,'error' => '없는 계정이거나 아이디 혹은 비밀번호가 맞지 않습니다.'
        ], 401);
      }else {
  
        if($parent) { 
          // 부모면 부모로그인
          Auth::guard('parents')->login($parent);
    
          $responseData = [
            'success' => true
            ,'msg' => '부모 로그인 성공'
            ,'user' => $parent
            ,'redirect_to' => 'parent/home'
          ];
        }else { 
          //아니면 자녀 로그인
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
    $parent = Auth::guard('parents')->user() ? true : false;
    $child = Auth::guard('children')->user() ? true : false;

    return response()->json([
      // 인증 사용자의 세션이 남아있으면 true, 없을경우 false
      'isSession' => ($parent || $child)
      ,'isParent' => $parent
      ,'isChild' => $child
    ], 200);
  }

  /**
   * 아이디 중복 체크
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
      return response()->json(['msg' => '영문 및 숫자조합 6 ~ 18글자만 사용가능 합니다.']);
    }

    // 아이디 중복 체크
    elseif($this->authRepository->findDuplicateAccount($account)) {
      // 중복된 아이디가 존재함
      return response()->json(['msg' => '동일한 아이디가 이미 존재합니다.']);
    }else {
      // 중복 아이디 없음
      return response()->json([
        'isPass' => true // 초록색 글자 여부
        // ,'msg' => '사용가능한 아이디 입니다.'
      ]);
    }
  }

  /**
   * 이메일 중복 체크
   * 
   * @param String $email
   * 
   * @return JSON $responseData
   */
  public function chkEmail($email) {
    // 영문 및 숫자로 이루어져있고 @ 과 . 가 반드시 포함된 이메일
    if(!preg_match('/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/', $email)) {
      return response()->json(['msg' => '맞지 않는 이메일 형식 입니다']);
    }

    // 이메일 중복 체크
    elseif($this->authRepository->findDuplicateEmail($email)) {
      // 중복된 이메일 존재함
      return response()->json(['msg' => '해당 메일로 가입한 계정이 이미 존재합니다.']);
    }else {
      // 중복 이메일 없음
      return response()->json([
        'isPass' => true // 초록색 글자 여부
      ]);
    }
  }

  /**
   * 이메일 전송
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function sendEmail(Request $request) {
    try {
      // 숫자 랜덤 선택
      $number = '0123456789';
      $randomNumber = Arr::random(str_split($number), 3);
      
      // 대문자 랜덤 선택
      $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = Arr::random(str_split($string), 3);
      
      // 숫자와 대문자 배열을 합침
      $mixed = array_merge($randomNumber, $randomString);
      
      // 배열을 섞음
      shuffle($mixed);
      
      // 섞인 배열을 문자열로 변환
      $randomCode = implode('', $mixed);

      // 메일 전송 탬플릿
      $template = view('email', [
        'code' => $randomCode
      ])->render();

      // 이메일 전송
      Mail::to($request->email)->send(new SendEmail($template));

      return response()->json([
        'success' => true,
        'msg' => '이메일 전송 성공',
        'code' => $randomCode
      ], 200);

    }catch (Throwable $th) {
      // 오류 로그 기록
      // Log::error('이메일 전송 실패: ' . $th->getMessage());

      return response()->json([
          'success' => false,
          'error' => '이메일 전송 실패'
      ], 500);
    }
  }

  /**
   * 가족코드 검사
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function chkFamCode($famCode) {
    // 공백이나 특수문자가 들어가 있는지
    if(preg_match('/[\s\W]/', $famCode)) { 
      return response()->json(['msg' => '공백 혹은 특수문자가 포함되어 있습니다.']);
    }

    // 영문 및 숫자로 이루어진 8글자가 아닌지
    elseif(!preg_match('/^[A-Z0-9]{8}$/', $famCode)) {
      return response()->json(['msg' => '맞지 않는 가족코드 형식 입니다.']);
    }

    // 가족코드 존재 여부 검사
    elseif(!$this->authRepository->findDuplicateFamilyCode($famCode)) {
      // 존재하지 않는 가족코드
      return response()->json(['msg' => '해당 가족코드는 존재하지 않습니다.']);
    }else {
      // 매칭됨
      return response()->json([
        'isPass' => true // 초록색 글자 여부
      ]);
    }
  }

  /**
   * 부모 정보 매칭
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function matchingParent(Request $request) {

    // 가족코드가 입력됫고 제대로 8글자가 왔다면
    if($request->famCode && (mb_strlen($request->famCode) === 8)) {
      $parent = $this->authRepository->findParentByFamilyCode($request->famCode);

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
      'error' => '부모 매칭 실패',
    ], 500);
  }

  /**
   * 회원가입 정보 저장
   * 
   * @param AuthenticationRequest $request // 커스텀 유효성 검사 리퀘스트
   * 
   * @return JSON $responseData
   */
  public function storeUser(AuthenticationRequest $request) {
    if(empty($request->all()) // 요청값이 아무것도 없거나
    || $request->missing(['account', 'password', 'passwordChk', 'name', 'tel', 'email']) // 필수값중에 하나라도 없으면
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
        // if($request->nick_name) {
        //   $insertData['nick_name'] = $request->nick_name;
        // }
        
        // 부모ID가 들어왔다면 자녀
        if($request->parentId) {

          // 자녀 레코드 생성
          try {
            $insertData['parent_id'] = $request->parentId;
            $child = $this->authRepository->createChild($insertData);

            // 리스폰스 데이터 작성
            $responseData = [
              'success' => true,
              'msg' => '자녀 회원작성 성공',
              // 'child' => $child,
              // 'redirect_to' => '/regist/complete'
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

            // 코드뷰 페이지에서 필요한 정보만 세팅
            // $parentInfo = [
            //   'name' => $parent->name,
            //   'famCode' => $parent->family_code,
            // ];

            // 리스폰스 데이터 작성
            $responseData = [
              'success' => true,
              'msg' => '부모 회원작성 성공',
              'famCode' => $parent->family_code,
              // 'parent' => $parentInfo,
              // 'redirect_to' => '/regist/parent/code',
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
   * 본인 확인 처리
   * 
   * @param AuthenticationRequest $request
   * 
   * @return JSON $responseData
   */
  public function identUser(AuthenticationRequest $request) {
    if(empty($request->all()) || $request->missing('password')) {
      return response()->json([
        'success' => false,
        'error' => '요청값이 없거나 필수적으로 들어가야할 정보가 없습니다.',
      ], 401);
    }else {
      
      // 비밀번호 확인 로직
      $user = Auth::guard('parents')->user() ?? Auth::guard('children')->user();

      if($user && Hash::check($request->password, $user->password)) {
        return response()->json([
          'success' => true,
          'msg' => '비밀번호 확인 성공',
        ], 200);
      }else {
        return response()->json([
          'success' => false,
          'error' =>  [ 'password' => ['0' => '비밀번호가 일치하지 않습니다.']],
        ], 422);
      }
    }
  }

  /**
   * 회원 정보 수정
   * 
   * @param AuthenticationRequest $request
   * 
   * @return JSON $responseData
   */
  public function modifyUser(AuthenticationRequest $request) {
    if(empty($request->all()) || // 요청값이 아무것도 없거나
      $request->missing(['name', 'tel', 'email']) // 필수값중에 하나라도 없으면
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
  
        // 필수 정보들을 루프를 돌려 값이 바뀐것만 세팅
        foreach(['name', 'email', 'tel'] as $field) {
          if($request->$field !== $user->$field) {
            $updateData[$field] = $request->$field;
          }
        }

        // 비밀번호 변경이 들어왔으면
        if($request->newPassword) {
          // 새 비밀번호를 해시화
          $updateData['password'] = Hash::make($request->newPassword);
        }
  
        // 닉네임이 들어왔고 들어온 닉네임이 기존에 있는 닉네임과 같지 않다면 세팅
        // if($request->nick_name && $request->nick_name !== $user->nick_name) {
        //   $updateData['nick_name'] = $request->nick_name;
        // }
        
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
            'column_val' => $user->$colName
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
  public function removeUser() {
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
        'column_val' => $user->$colName
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

  /**
   * 자녀 승인 처리
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function applyChild(Request $request) {

    $updateData = [
      'app_state' => 1
    ];

    $userInfo = [
      'column_name' => 'child_id',
      'column_val' => $request->childId
    ];

    $this->authRepository->updateUser($userInfo, $updateData);

    return response()->json([
      'success' => true,
      'msg' => '자녀 승인 성공',
    ], 200);
  }

  /**
   * 미승인 자녀 물리 삭제 처리
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function deleteChild(Request $request) {

    $userInfo = [
      'column_name' => 'child_id',
      'column_val' => $request->childId
    ];

    $this->authRepository->deleteUser($userInfo);

    return response()->json([
      'success' => true,
      'msg' => '미승인 자녀 물리 삭제 성공',
    ], 200);
  }

  /**
   * 아이디 비밀번호 찾기 처리
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function findUser(Request $request) {
    $userInfo = $this->authRepository->findUser($request);

    if(empty($userInfo)) {
      return response()->json([
        'success' => false,
        'error' => '일치하는 계정의 정보가 없습니다.',
      ], 401);
    }

    return response()->json([
      'success' => true,
      'msg' => '찾기 성공',
      'user' => $userInfo
    ]);
  }

  /**
   * 회원 정보 세팅
   * 
   * @param Request $request
   * 
   * @return JSON $responseData
   */
  public function userInfo(Request $request) {
    $userInfo = $this->authRepository->findUserById($request);

    return response()->json([
      'success' => true,
      'msg' => '찾기 성공',
      'user' => $userInfo
    ]);
  }

  /**
   * 비밀번호 재발급 처리
   * 
   * @param AuthenticationRequest $request
   * 
   * @return JSON $responseData
   */
  public function resetPassword(AuthenticationRequest $request) {
    try {
      $userInfo = [
        'column_name' => $request->userType.'_id',
        'column_val' => $request->userId
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
      ], 500);
    }
  }
  
  // --------------------------- V001 del start -----------------------------
  // 
  // /**
  //  * 회원 비밀번호 변경 처리
  //  * 
  //  * @param PasswordRequest $request
  //  * 
  //  * @return JSON $responseData
  //  */
  // public function changePassword(PasswordRequest $request) {
  //   if(empty($request->all()) || $request->missing(['password', 'newPassword', 'newPasswordChk'])) { 
  //     return response()->json([
  //       'success' => false,
  //       'error' => '요청값이 없거나 필수적으로 들어가야할 정보가 없습니다.',
  //     ], 401);
  //   }else {
  //     try {
  //       // 로그인한 유저 정보를 세팅
  //       if(Auth::guard('parents')->check()) {
  //         $user = Auth::guard('parents')->user();
  //         $colName = 'parent_id';
  //       }else {
  //         $user = Auth::guard('children')->user();
  //         $colName = 'child_id';
  //       }
        
  //       $userInfo = [
  //         'column_name' => $colName,
  //         'column_val' => $user->$colName
  //       ];

  //       $updateData = [
  //         'password' => Hash::make($request->newPassword)
  //       ];

  //       // 비밀번호 업데이트 실행
  //       $this->authRepository->updateUser($userInfo, $updateData);

  //       $responseData = [
  //         'success' => true,
  //         'msg' => '비밀번호 변경 성공'
  //       ];

  //       return response()->json($responseData, 200);
        
  //     }catch(Throwable $th) {
  //       return response()->json([
  //         'success' => false,
  //         'error' => '비밀번호 변경 실패: ' . $th->getMessage(),
  //       ], 500);
  //     }
  //   }
  // }
  // 
  // /**
  //  * 자녀 연결 레코드 조회
  //  * 
  //  * @param Request $request
  //  * 
  //  * @return JSON $responseData
  //  */
  // public function childManyInfo() {
  //   $child = Auth::guard('children')->user();

  //   // 자녀 레코드 + 연결된 미션,지출 레코드
  //   $childInfo = $this->authRepository->childInfoWithMissionsAndTransactions($child->child_id);

  //   return response()->json([
  //     'success' => true,
  //     'child' => $childInfo,
  //   ]);
  // }
  // 
  // /**
  //  * 가족코드 부모 검사
  //  * 
  //  * @param Request $request
  //  * 
  //  * @return JSON $responseData
  //  */
  // public function childRegistMatching(AuthRequest $request) {
  //   // 가족코드가 입력됫고 제대로 8글자가 왔다면
  //   if($request->fam_code && (mb_strlen($request->fam_code) === 8)) {
  //     $parent = $this->authRepository->findParentByFamilyCode($request->fam_code);
  // 
  //     if($parent) {
  //       $responseData = [ 
  //         'success' => true,
  //         'msg' => '부모 매칭 성공',
  //         'parent' => $parent,
  //       ];
  // 
  //       return response()->json($responseData, 200);
  //     }
  //   }
  //   return response()->json([
  //     'success' => false,
  //     'error' => [ 'fam_code' => ['0' => '존재하지 않는 코드입니다.']],
  //   ], 422);
  // }
  //
  // /**
  //  * 자녀 부모ID 갱신 처리
  //  */
  // public function modifyReMatching(Request $request) {
  //   if(empty($request->all()) || $request->missing(['parent_id'])) {
  //     return response()->json([
  //       'success' => false,
  //       'error' => '요청값이 없거나 필수적으로 들어가야할 정보가 없습니다.',
  //     ], 401);
  //   }else {
  //     try {
  //       // 로그인한 유저 정보를 세팅
  //       if(!Auth::guard('children')->check()) {
  //         return response()->json([
  //           'success' => false,
  //           'error' => '잘못된 접근이 감지되었습니다.',
  //         ], 401);
  //       }else {
  //         $user = Auth::guard('children')->user();
  //         $colName = 'child_id';
  //       }
        
  //       $userInfo = [
  //         'column_name' => $colName,
  //         'column_val' => $user->$colName
  //       ];

  //       $updateData = [
  //         'parent_id' => $request->parent_id
  //       ];

  //       // 부모 변경 실행
  //       $this->authRepository->updateUser($userInfo, $updateData);

  //       $responseData = [
  //         'success' => true,
  //         'msg' => '부모 변경 성공'
  //       ];

  //       return response()->json($responseData, 200);

  //     }catch(Throwable $th) {
  //       return response()->json([
  //         'success' => false,
  //         'error' => '부모 변경 실패: ' . $th->getMessage(),
  //       ], 500);
  //     }
  //   }
  // }
  // 
  // --------------------------- V001 del end -----------------------------

}
