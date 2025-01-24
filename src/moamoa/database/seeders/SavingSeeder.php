<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\Saving;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SavingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ko_KR');

        $data = [
            ['financial_product' => '7일', 'period' => '7', 'amount' => 100, 'interest_rate' => rand(1.5, 8.0), 'type' => '0'], 
            ['financial_product' => '14일', 'period' => '14', 'amount' => 100, 'interest_rate' => rand(1.5, 8.0), 'type' => '0'], 
            ['financial_product' => '21일', 'period' => '21', 'amount' => 100, 'interest_rate' => rand(1.5, 8.0), 'type' => '1'], 
            ['financial_product' => '28일', 'period' => '28', 'amount' => 100, 'interest_rate' => rand(1.5, 8.0), 'type' => '1'], 
            ['financial_product' => '30일', 'period' => '30', 'amount' => 100, 'interest_rate' => 3, 'type' => '1'], 
            ['financial_product' => '35일', 'period' => '35', 'amount' => 100, 'interest_rate' => rand(1.5, 8.0), 'type' => '1'], 
            ['financial_product' => '42일', 'period' => '42', 'amount' => 100, 'interest_rate' => rand(1.5, 8.0), 'type' => '1'], 
            ['financial_product' => '49일', 'period' => '49', 'amount' => 100, 'interest_rate' => rand(1.5, 8.0), 'type' => '1'], 
        ];

        Saving::create($data); // 데이터 저장
    }
}
