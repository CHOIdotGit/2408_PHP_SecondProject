<?php

namespace App\Http\Requests;

use App\Rules\ExistsFamilyRule;
use App\Rules\UniqueFamilyRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthRequest extends FormRequest {
  // 어스 유효성 룰 정책. 로그인과 회원가입만 들어갈 예정
  public function rules() {
    $rules = [
      // 영문 대소문자 시작 및 숫자조합 6 ~ 18글자
      // 'account' => ['required', 'between:6,18', 'regex:^[a-zA-Z][a-zA-Z0-9]$'] 
      // 영대문자 및 숫자 반드시 포함 6 ~ 20글자 
      // ,'password' => ['required', 'between:6,20', 'regex:/^[a-zA-Z0-9!@]+$/']
      
      // 테스트용으로 위의 정규식 잠깐 제외
      'account' => ['required']
      ,'password' => ['required']
    ];

    // 로그인 체크, 부모 테이블이나 자녀 테이블에 해당 아이디가 있는지 확인함
    if($this->routeIs('auth.login')) {
      $rules['account'][] = new ExistsFamilyRule;
    }

    // 회원가입 실행시 유효성 체크
    elseif($this->routeIs('auth.store.user')) {
      // 유니크 ID가 두 테이블(부모, 자녀)에 존재하는지 검사
      $rules['account'][] = new UniqueFamilyRule;
      $rules['password_chk'] = ['required', 'same:password'];
      $rules['name'] = ['required', 'between:1,20', 'regex:/^[a-zA-Z가-힣][a-zA-Z0-9가-힣]+$/'];
      $rules['email'] = ['required'];
      $rules['tel'] = ['required'];
      $rules['nick_name'] = ['nullable', 'between:1,20', 'regex:/^[a-zA-Z가-힣][a-zA-Z0-9가-힣]+$/'];
      $rules['profile'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'];
      $rules['family_code'] = ['nullable', 'unique:parents,family_code'];
    }

    return $rules;
  }

  public function messages() {
    return [
      'account.required' => '아이디를 입력해주세요.'
      ,'account.regex' => '아이디 형식이 맞지 않습니다.'
      ,'password.required' => '비밀번호를 입력해주세요.'
      ,'password.regex' => '비밀번호 형식이 맞지 않습니다.'
      ,'password_chk.required' => '비밀번호 확인을 입력해주세요.'
      ,'password_chk.same' => '비밀번호가 서로 일치하지 않습니다.'
      ,'name.required' => '이름을 입력해주세요.'
      ,'name.regex' => '단어 형식이 맞지 않습니다.'
      ,'nick_name.regex' => '단어 형식이 맞지 않습니다.'
      ,'email.required' => '이메일을 입력해주세요.'
      ,'tel.required' => '전화번호를 입력해주세요.'
    ];
  }

  // 실패시 처리
  protected function failedValidation(Validator $validator) {
    $response = response()->json([
      'success' => false
      ,'error' => $validator->errors()
    ], 422);

    throw new HttpResponseException($response);
  }
}