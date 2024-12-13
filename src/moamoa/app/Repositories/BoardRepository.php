<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\Child;
use App\Models\ParentModel;

class BoardRepository extends BaseRepository {
    public function model() {
        // 사용할 모델을 기재
        return [
            Child::class
            ,ParentModel::class
        ];
    }
}
