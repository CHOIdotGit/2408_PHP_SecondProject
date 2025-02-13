<?php

namespace App\Http\Requests;

use App\Rules\CheckPasswordRule;
use App\Rules\ExistsFamilyRule;
use App\Rules\NotSameFamilyCodeRule;
use App\Rules\UniqueFamilyRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthenticationRequest extends FormRequest {
  /**
   * 요청에 적용되는 유효성 검사 규칙을 가져옵니다.
   *
   * @return array<string, mixed>
   */
  public function rules() {
    $rules = [
      'account' => ['required']
      ,'password' => ['required']
    ];

    // 로그인 체크, 부모 테이블이나 자녀 테이블에 해당 아이디가 있는지 확인함
    if($this->routeIs('auth.login')) {
      $rules['account'][] = new ExistsFamilyRule;
    }

    // 회원가입 or 회원수정
    elseif($this->routeIs('auth.store.user')
        || $this->routeIs('auth.modify.user')
    ) {

      // 들어온게 회원가입이면
      if($this->routeIs('auth.store.user')) {
        $rules['account'][] = new UniqueFamilyRule;
        $rules['password'][] = 'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).{6,18}$/';
        $rules['passwordChk'] = ['required', 'same:password'];

      // 회원수정 이라면
      }elseif($this->routeIs('auth.modify.user')) {
        $rules['account'] = ['nullable'];
        // $rules['password'][] = new CheckPasswordRule;
        $rules['password'] = ['nullable'];

        // 둘중 하나라도 입력되면 필수사항
        if($this->filled('newPassword') || $this->filled('newPasswordChk')) {
          $rules['newPassword'] = ['required', 'different:password', 'regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).{6,18}$/'];
          $rules['newPasswordChk'] = ['required', 'same:newPassword'];
        // 입력 안햇다면 통과
        }else {
          $rules['newPassword'] = ['nullable'];
          $rules['newPasswordChk'] = ['nullable'];
        }
      }

      // 그외 필수 체크 정보들
      $rules['name'] = ['required', 'between:1,20', 'regex:/^[a-zA-Z가-힣][a-zA-Z0-9가-힣]+$/'];
      $rules['email'] = ['required', 'regex:/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/']; // 영문숫자 @ 영문숫자 . 영문
      $rules['tel'] = ['required', 'regex:/^010\d{8,9}$/']; // 010 반드시 포함 뒤에 8 ~ 9자의 숫자  // 'regex:/^\d{10,11}$/' 10 ~ 11자의 숫자
      // $rules['nick_name'] = ['nullable', 'between:1,20', 'regex:/^[a-zA-Z가-힣][a-zA-Z0-9가-힣]+$/']; // 첫문자는 영문대소문자한글 그뒤는 숫자포함
    }

    // 본인 확인
    elseif($this->routeIs('auth.ident.user')) {
      $rules['account'] = ['nullable'];
      $rules['password'][] = new CheckPasswordRule;
    }

    return $rules;
  }

  public function messages() {
    return [
      'account.required' => '아이디를 입력해 주세요.'
      ,'account.regex' => '아이디 형식이 맞지 않습니다.'
      ,'password.required' => '비밀번호를 입력해주세요.'
      ,'password.regex' => '비밀번호 형식이 맞지 않습니다.'
      ,'passwordChk.required' => '비밀번호 확인을 입력해주세요.'
      ,'passwordChk.same' => '비밀번호가 서로 일치하지 않습니다.'
      ,'name.required' => '이름을 입력해 주세요.'
      ,'name.regex' => '이름 형식이 맞지 않습니다.'
      // ,'nick_name.regex' => '단어 형식이 맞지 않습니다.'
      ,'email.required' => '이메일을 입력해 주세요.'
      ,'email.regex' => '이메일 형식이 맞지 않습니다.'
      ,'tel.required' => '전화번호를 입력해 주세요.'
      ,'tel.regex' => '번호 형식이 맞지 않습니다.'
      // ,'fam_code.required' => '가족코드를 입력해 주세요.'
      // ,'fam_code.regex' => '단어 형식이 맞지 않습니다.'
      ,'newPassword.required' => '새 비밀번호를 입력해주세요.'
      ,'newPassword.regex' => '새 비밀번호 형식이 맞지 않습니다.'
      ,'newPassword.different' => '현재 비밀번호와 동일할 수 없습니다.'
      ,'newPasswordChk.required' => '새 비밀번호 확인을 입력해주세요.'
      ,'newPasswordChk.same' => '새 비밀번호와 서로 일치하지 않습니다.'
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