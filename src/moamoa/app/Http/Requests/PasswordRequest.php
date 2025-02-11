<?php

namespace App\Http\Requests;

use App\Rules\CheckPasswordRule;
use App\Rules\NotSamePasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PasswordRequest extends FormRequest {

  /**
   * 요청에 적용되는 유효성 검사 규칙을 가져옵니다.
   *
   * @return array<string, mixed>
   */
  public function rules() {
    $rules = [
      // 기본적으로 로그인한 유저의 비밀번호 일치를 체크
      'password' => ['required', new CheckPasswordRule]
    ]; 

    // 비밀번호 변경이 실행될 경우
    if($this->routeIs('auth.change.password')) {
      $rules['newPassword'] = ['required', 'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).{6,18}$/', new NotSamePasswordRule]; // 영문 숫자 조합 6자리 이상
      $rules['newPasswordChk'] = ['required', 'same:newPassword'];
    }
    // /^(?=(.*[a-zA-Z])(?=.*\d)|(?=.*[a-zA-Z])(?=.*[^\w\d])|(?=.*\d)(?=.*[^\w\d])).{6,18}$/ // 영문 숫자 특수기호 두종류 이상 6~18자리
    return $rules;
  }

  public function messages() {
    return [
      'password.required' => '비밀번호를 입력해주세요.'
      // ,'password.regex' => '비밀번호 형식이 맞지 않습니다.'
      ,'newPassword.required' => '비밀번호를 입력해주세요.'
      ,'newPassword.regex' => '비밀번호 형식이 맞지 않습니다.'
      ,'newPasswordChk.required' => '비밀번호 확인을 입력해주세요.'
      ,'newPasswordChk.same' => '비밀번호가 서로 일치하지 않습니다.'
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