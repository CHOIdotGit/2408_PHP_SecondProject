<?php

namespace App\Repositories;

use App\Models\Child;
use App\Models\Mission;
use App\Models\ParentModel;
use App\Models\Transaction;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Illuminate\Support\Facades\DB;
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
            Child::class
            ,ParentModel::class
            ,Mission::class
            ,Transaction::class
        ];
    }

    public function createParent($insertData) {
        DB::beginTransaction();
        try {
            $parent = $this->parent->create($insertData);
            DB::commit();

            return $parent;
        }catch(Throwable $th) {
            DB::rollBack();
            throw $th;
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
