<?php

namespace App\Rules;

use App\Models\ParentModel;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class NotSameFamilyCodeRule implements Rule {
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
    
    $result = false;

    if(Auth::guard('children')->check()) {
      $parent = ParentModel::select('parent_id', 'family_code')
        ->where('parent_id', Auth::guard('children')->user()->parent_id)
        ->first();

      if($parent && $value === $parent->family_code) {
        $result = true;
      }
    }

    // 서로 같지 않아야만 통과
    return !$result;
  }

  /**
   * 유효성 검사 오류 메시지 반환
   *
   * @return string
   */
  public function message() {
    return '동일한 부모는 매칭할 수 없습니다.';
  }
}