<?php

namespace App\Console\Commands;

use App\Models\Child;
use App\Models\Mission;
use App\Models\ParentModel;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldDataCommand extends Command {
  /**
   * 콘솔 명령어의 이름과 시그니처
   *
   * @var string
   */
  protected $signature = 'records:delete-old';

  /**
   * 콘솔 명령어 설명
   *
   * @var string
   */
    protected $description = '소프트 삭제된 레코드들중 6개월이 지난 레코드들은 물리적으로 삭제';

  /**
   * 콘솔 명령어 실행
   *
   * @return int
   */
  public function handle() {
    // 삭제 실행할 테이블 목록
    $models = [
      ParentModel::class,
      Child::class,
      Mission::class,
      Transaction::class
    ];

    // 지금부터 6개월 전의 데이터
    $sixMonthDate = Carbon::now()->subMonths(6);

    foreach ($models as $model) {
      $deletedRecords = $model::onlyTrashed() // 소프트 딜리트 레코드 검색
        ->where('deleted_at', '<=', $sixMonthDate) // 6개월 전의 데이터보다 작거나 같으면
        ->forceDelete(); // 물리 삭제 실행
      
      $this->info(class_basename($model) . " 테이블의 {$deletedRecords}개의 레코드가 삭제되었습니다.");
    }
  }
}