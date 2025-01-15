<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CheckPasswordRule implements Rule {
  /**
   * 새로운 규칙 인스턴스 생성
   *
   * @return void
   */
  public function __construct() {
    //
  }

  /**
   * 유효성 검사 규칙 통과 여부 확인
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value) {

    $check = false;

    if(Auth::guard('parents')->check()) {
      $check = Hash::check($value, Auth::guard('parents')->user()->password);
    }else if(Auth::guard('children')->check()) {
      $check = Hash::check($value, Auth::guard('children')->user()->password);
    }

    return $check;
  }

  /**
   * 유효성 검사 오류 메시지 반환
   *
   * @return string
   */
  public function message() {
    return '현재 비밀번호와 일치하지 않습니다.';
  }
}