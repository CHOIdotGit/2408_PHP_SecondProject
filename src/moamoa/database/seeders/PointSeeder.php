<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\Point;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ko_KR');
        $children = Child::whereBetween('child_id', [1, 3])->pluck('child_id');
        $code = [0, 1, 2, 4];
        // $data = [
        //     'child_id' => $child,
        //     'point' => 1000000,
        //     'point_code' => $code[array_rand($code)],
        //     'payment_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d'),
        // ];
        
        // Point::create($data);
        for ($i = 0; $i < 100; $i++) {
            foreach ($children as $child) {
                $data = [
                    'child_id' => $child, // 각 child_id 값을 삽입
                    'point' => 10000,
                    'point_code' => $code[array_rand($code)], // 랜덤 point_code
                    'payment_at' => $faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d'), // 랜덤 날짜
                ];

                Point::create($data); // 데이터 저장
            }
        }
    }
}
