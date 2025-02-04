<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\SavingProduct;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SavingProductSeeder extends Seeder
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
            ['saving_product_name' => '7일', 'saving_product_period' => '7', 'saving_product_amount' => 100, 'saving_product_interest_rate' => 3, 'saving_product_type' => '0'], 
            ['saving_product_name' => '14일', 'saving_product_period' => '14', 'saving_product_amount' => 100, 'saving_product_interest_rate' => 3, 'saving_product_type' => '0'], 
            ['saving_product_name' => '21일', 'saving_product_period' => '21', 'saving_product_amount' => 100, 'saving_product_interest_rate' => 3, 'saving_product_type' => '1'], 
            ['saving_product_name' => '28일', 'saving_product_period' => '28', 'saving_product_amount' => 100, 'saving_product_interest_rate' => 3, 'saving_product_type' => '1'], 
            ['saving_product_name' => '35일', 'saving_product_period' => '35', 'saving_product_amount' => 100, 'saving_product_interest_rate' => 3, 'saving_product_type' => '1'], 
            ['saving_product_name' => '42일', 'saving_product_period' => '42', 'saving_product_amount' => 100, 'saving_product_interest_rate' => 3, 'saving_product_type' => '1'], 
            ['saving_product_name' => '49일', 'saving_product_period' => '49', 'saving_product_amount' => 100, 'saving_product_interest_rate' => 3, 'saving_product_type' => '1'], 
        ];
        // 3에 한국은행 기준금리 담을 예정 / 계산은 컨트롤러의 쿼리문에서

        foreach ($data as $row) {
            SavingProduct::create($row);
        }
        // Saving::create($data); // 데이터 저장
    }
}
