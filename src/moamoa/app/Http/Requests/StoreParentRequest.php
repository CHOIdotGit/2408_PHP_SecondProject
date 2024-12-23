<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParentRequest extends FormRequest {
  /**
   * 사용자가 이 요청을 수행할 권한이 있는지 확인합니다.
   *
   * @return bool
   */
  public function authorize() {
    return true;
  }

  /**
   * 요청에 적용되는 유효성 검사 규칙을 가져옵니다.
   *
   * @return array<string, mixed>
   */
  public function rules() {
    return [
      'account' => ['required', 'regex:^[a-z][a-z0-9]{6,20}$'] // 영어 소문자로 시작하는 영어 소문자 및 숫자로 이루어진 4~20글자
      ,'password' => ['required','between:6,20', 'regex:/^[a-zA-Z0-9!@]+$/'] // 영대문자숫자포함 6~20글자
      ,'password_chk' => ['same:password'] 
      ,'name' => ['required']
      ,'email' => ['required|email']
      ,'tel' => ['required']
      // ,'family_code' => ['required']
    ];
  }

  // 틀릴 시 출력할 메세지 핸들링
  public function messages() {
      return [
        'account.required' => '아이디가 비어있습니다.'
        ,'account.regex' => '아이디는 영문 및 숫자조합 6~20 자만 입력가능 합니다.'
        ,'password_chk.same' => '비밀번호와 비밀번호 확인이 일치하지 않습니다.'
        ,'name.required' => '이름이 비어있습니다.'
        ,'email.required' => '이메일이 비어있습니다.'
        ,'tel.required' => '전화번호가 비어있습니다.'
      ];
  }
}