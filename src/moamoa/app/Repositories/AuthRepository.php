<?php

namespace App\Repositories;

class AuthRepository extends Repositories {
  
  // 아이디 중복 체크
  public function findDuplicateAccount($account) {
    $parent = $this->parent->where('account', $account)->first();
    $child = $this->child->where('account', $account)->first();

    // true : 중복 아이디 있음, false : 중복 없음
    return ($parent || $child) ? true : false;
  }

  // 부모 가족 코드 중복 체크
  public function findDuplicateFamilyCode($family_code) {
    $parent = $this->parent->where('family_code', $family_code)->first();

    // true : 중복 있음, false : 중복 없음
    return ($parent) ? true : false;
  }
}
