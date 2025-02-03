<?php

namespace Database\Seeders;

use App\Models\SavingSignUp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SavingSignUpSeeder extends Seeder
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
            ['child_id' => 1, 'saving_id' => rand(1, 7), 'start_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d'), 'end_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d')], 
            ['child_id' => 2, 'saving_id' => rand(1, 7), 'start_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d'), 'end_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d')], 
            ['child_id' => 3, 'saving_id' => rand(1, 7), 'start_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d'), 'end_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d')], 
        ];

        // saving_id에 따라 start_at과 end_at을 설정
        foreach ($data as &$item) {
            // saving_id에 따른 기간 계산 (7일 * saving_id)
            $savingPeriod = 7 * $item['saving_id'];

            // start_at을 기준으로 end_at 계산
            $item['end_at'] = (new \DateTime($item['start_at']))->modify("+$savingPeriod days")->format('Y-m-d');
        }

        foreach ($data as $row) {
            SavingSignUp::create($row);
        }
    }
}
