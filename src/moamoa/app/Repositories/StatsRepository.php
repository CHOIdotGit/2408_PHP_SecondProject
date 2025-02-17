<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class StatsRepository.
 */
class StatsRepository extends Repositories
{
    public function eachCategoryStats($startOfMonth, $endOfMonth, $child_id) {
        return $this->transaction::select(
            'category',
            DB::raw('SUM(amount) as total_amount')
        )
        ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])
        ->where('child_id', $child_id)
        ->where('transaction_code', 1)
        ->groupBy('category')
        ->orderBy('category')
        ->get();
    }
}
