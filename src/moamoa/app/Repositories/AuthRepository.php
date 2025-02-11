<?php

namespace App\Repositories;

class AuthRepository extends Repositories {
  // 아이디 중복 체크
  public function findDuplicateAccount($account) {
    $parent = $this->parent->select('account')->where('account', $account)->first();
    $child = $this->child->select('account')->where('account', $account)->first();

    // true : 중복 있음, false : 중복 없음
    return ($parent || $child) ? true : false;
  }

  // 이메일 중복 체크
  public function findDuplicateEmail($email) {
    $parent = $this->parent->select('email')->where('email', $email)->first();
    $child = $this->child->select('email')->where('email', $email)->first();

    // true : 중복 있음, false : 중복 없음
    return ($parent || $child) ? true : false;
  }

  // 부모 가족 코드 중복 체크
  public function findDuplicateFamilyCode($family_code) {
    $parent = $this->parent->select('family_code')->where('family_code', $family_code)->first();

    // true : 중복 있음, false : 중복 없음
    return ($parent) ? true : false;
  }

  // 가족 코드로 부모 정보 획득
  public function findParentByFamilyCode($family_code) {
    return $this->parent->where('family_code', $family_code)->first();
  }

  // 계정이름으로 부모 정보 획득
  public function findParentByAccount($account) {
    return $this->parent->where('account', $account)->first();
  }

  // 계정이름으로 자녀 정보 획득
  public function findChildByAccount($account) {
    return $this->child->where('account', $account)->first();
  }

  // 부모 정보와 연결 자녀들 정보 획득
  public function parentInfoWithChildren($parent_id) {
    return $this->parent->where('parent_id', $parent_id)->with('children')->first();
  }

  // 자녀 정보 획득
  public function childInfoWithParent($child_id) {
    return $this->child->where('child_id', $child_id)->with('parent')->first();
  }

  // 자녀 연결 정보 획득
  public function childInfoWithMissionsAndTransactions($child_id) {
    return $this->child->where('child_id', $child_id)->with(['missions', 'transactions'])->first();
  }
}
