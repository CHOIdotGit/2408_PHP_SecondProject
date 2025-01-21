<?php

namespace Database\Seeders;

use App\Models\ParentModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // ['account' => 'wndus', 'password' => Hash::make('wndus'), 'name' => '김주연', 'nick_name' => '춘식이', 'email' => 'wndus@wndus.com', 'tel' => '01000000000', 'profile' => '/profile/cnstlrdl.webp', 'family_code' => 'ab123456'],
            [
                'account' => 'wndus'
                , 'password' => Hash::make('wndus')
                , 'name' => '김주연'
                , 'nick_name' => '춘식이'
                , 'email' => 'wndus@wndus.com'
                , 'tel' => '01000000000'
                , 'profile' => '/profile/cnstlrdl.webp'
                , 'family_code' => 'ab123456'
            ],
        ];

        foreach($data as $item) {
            ParentModel::create($item);
        }

        $faker = Faker::create('ko_KR');

        // $i개 추가
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'account' => $faker->unique(true)->regexify('[a-z0-9]{6,20}'),
                'password' => Hash::make('qwer1234'),
                'name' => mb_substr($faker->name, 0, rand(2, 3)),
                'nick_name' => mb_substr($faker->optional()->realText(10, 1), 0, rand(2, 5)),
                'email' => $faker->unique(true)->safeEmail(),
                'tel' => '010' . $faker->numerify('########'),
                'profile' => $faker->optional()->randomElement(['/user-img/default.webp']),
                'family_code' => strtoupper(implode('', array_merge(range('A', 'Z'), range(0, 9))))[rand(0, 35)] . rand(10000000, 99999999),
            ];

            // Eloquent를 사용해 데이터 삽입
            ParentModel::create($data);  // Eloquent 모델을 사용하여 삽입
        }
    }
}
