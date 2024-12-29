<?php

namespace App\Repositories;

use App\Models\Child;
use App\Models\Mission;
use App\Models\ParentModel;
use App\Models\Transaction;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

    public function createParent($insertData) {
      DB::beginTransaction();

      try {
        // 영대문자와 숫자를 각각 정의
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
  
        // 각 카테고리에서 중복 없이 4개씩 선택
        $randomLetters = Str::random(4, $letters); // 영대문 4개
        $randomNumbers = Str::random(4, $numbers); // 숫자 4개
  
        // 섞어서 랜덤 8자리 문자열 생성
        $randomString = str_shuffle($randomLetters . $randomNumbers);

        // 생성한 문자열을 인서트 데이터에 세팅
        $insertData['family_code'] = $randomString;

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

    public function createChild($insertData) {
        DB::beginTransaction();
        try {
            $child = $this->child->create($insertData);
            DB::commit();

            return $child;
        }catch(Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
