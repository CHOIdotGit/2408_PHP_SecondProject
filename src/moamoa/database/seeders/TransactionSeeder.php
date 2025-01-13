<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // ['parent_id' => 1, 'child_id' => 1, 'category' => rand(0, 3), 'transaction_code' => rand(0, 1), 'title' => '집 가고 싶다', 'amount' => round(rand(100,50000) / 100) * 100, 'transaction_date' => '', 'memo' => ''],
        ];
        foreach($data as $item) {
            Transaction::create($item);
        }
    }
}
