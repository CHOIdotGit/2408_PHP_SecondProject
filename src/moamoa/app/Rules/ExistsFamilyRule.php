<?php

namespace App\Rules;

use App\Models\Child;
use App\Models\ParentModel;
use Illuminate\Contracts\Validation\Rule;

class ExistsFamilyRule implements Rule {
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
    // 부모 테이블에서 'account' 값이 존재하는지 확인
    $parents = ParentModel::select('account')->where('account', $value)->exists();

    // 자녀 테이블에서 'account' 값이 존재하는지 확인
    $children = Child::select('account')->where('account', $value)->exists();
    
    // 두 테이블 중에 하나라도 존재하면 통과
    return $parents || $children;
  }

  /**
   * 유효성 검사 오류 메시지 반환
   *
   * @return string
   */
  public function message() {
    return '해당 계정은 존재하지 않습니다.';
  }
}