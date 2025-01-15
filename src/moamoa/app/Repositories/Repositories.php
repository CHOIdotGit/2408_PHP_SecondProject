<?php

namespace App\Repositories;

use App\Models\Child;
use App\Models\Mission;
use App\Models\ParentModel;
use App\Models\Transaction;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Throwable;

class Repositories extends BaseRepository {
  protected $parent;
  protected $child;
  protected $mission;
  protected $transaction;

  public function __construct(ParentModel $parent, Child $child, Mission $mission, Transaction $transaction) {
    $this->parent = $parent;
    $this->child = $child;
    $this->mission = $mission;
    $this->transaction = $transaction;
  }
  
  public function model() {
    // 사용할 모델을 기재
    return [
      $this->child
      ,$this->parent
      ,$this->mission
      ,$this->transaction
    ];
  }

  // 부모 레코드 생성
  public function createParent($insertData) {
    
    try {
      DB::beginTransaction();

      // 숫자 랜덤 선택
      $number = '0123456789';
      $randomNumber = Arr::random(str_split($number), 4);
      
      // 대문자 랜덤 선택
      $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = Arr::random(str_split($string), 4);
      
      // 숫자와 대문자 배열을 합침
      $mixed = array_merge($randomNumber, $randomString);
      
      // 배열을 섞음
      shuffle($mixed);
      
      // 섞인 배열을 문자열로 변환
      $randomCode = implode('', $mixed);

      // 생성한 문자열을 인서트 데이터에 세팅
      $insertData['family_code'] = $randomCode;

      // 레코드 생성을 실행
      $parent = $this->parent->create($insertData);

      // 무사히 완료되면 커밋
      DB::commit();

      return $parent;
    }catch(Throwable $th) {
      DB::rollBack();

      // 가족코드 중복 오류시 재실행
      $th->getCode() === 1062
      ? $this->createParent($insertData)
      : throw $th; // 아니면 예외 처리
    }
  }

  // 자녀 레코드 생성
  public function createChild($insertData) {
    
    try {
      DB::beginTransaction();

      $child = $this->child->create($insertData);

      DB::commit();

      return $child;
    }catch(Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  // 부모, 자녀 정보 업데이트
  public function updateUser($userInfo, $updateData) {
    try {
      DB::beginTransaction();

      // 부모인지 자녀인지 체크
      $modify = $userInfo['column_name'] === 'parent_id' ? $this->parent : $this->child;
      
      // 들어온 정보로 업데이트
      $modify->where($userInfo['column_name'], $userInfo['column_id'])
        ->update($updateData);

      DB::commit();

    }catch(Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  // 부모, 자녀 정보 삭제
  public function removeUser($userInfo) {
    try {
      DB::beginTransaction();

      // 부모인지 자녀인지 체크
      $modify = $userInfo['column_name'] === 'parent_id' ? $this->parent : $this->child;
      
      // 들어온 정보를 삭제
      $modify->where($userInfo['column_name'], $userInfo['column_id'])
        ->delete();

      DB::commit();
      
    }catch(Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }
}
