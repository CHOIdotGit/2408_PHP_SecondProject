<?php

namespace Database\Seeders;

use App\Models\Child;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChildrenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = [
        //     ['parent_id' => 1, 'account' => 'guswls', 'password' => Hash::make('guswls'), 'name' => '배현진', 'nick_name' => '공주', 'email' => 'guswls@guswls.com', 'tel' => '01000000001',  'profile' => '/profile/cute_girl_standing_and_smiling_character_cartoon_logo_hand_drawn_art_illustration.jpg',],
        //     ['parent_id' => 1, 'account' => 'gustjr', 'password' => Hash::make('gustjr'), 'name' => '김현석', 'nick_name' => 'qwe123', 'email' => 'gustjr@gustjr.com', 'tel' => '01000000002',  'profile' => '/profile/NicePng_tumblr-icon-png_1540854.png',],
        //     ['parent_id' => 1, 'account' => 'tkdals', 'password' => Hash::make('tkdals'), 'name' => '최상민', 'nick_name' => '뚤기', 'email' => 'tkdals@tkdals.com', 'tel' => '01000000003',  'profile' => '/profile/JEMA_GER_1653-04.jpg',], 
        // ];
        // foreach($data as $item) {
        //     Child::create($item);
        // }
        $faker = Faker::create();
        $data = [];

        for ($i = 0; $i < 20000; $i++) {
            $data[] = [
                'parent_id' => rand(70, 8069),
                'account' => $faker->unique()->regexify('[A-Za-z0-9]{1,20}'),
                'password' => Hash::make('qwer1234'),
                'name' => $faker->lexify(str_repeat('?', 20)), // 최대 20글자
                'nick_name' => $faker->optional()->lexify(str_repeat('?', 20)), // 최대 20글자, null 허용
                'email' => $faker->unique()->safeEmail(), // 최대 100글자
                // 'tel' => $faker->numerify(str_repeat('#', 11)), // 숫자만, 최대 30글자 이하
                'tel' => '010' . $faker->numerify('########'), // 010으로 시작하고 나머지는 랜덤 숫자
                'profile' => $faker->optional()->imageUrl(100, 100, 'people') ?: '/user-img/default.webp',
                'family_code' => $faker->unique()->regexify('[A-Za-z0-9]{8}') // 8자리 숫자와 영문 조합
            ];

            // 1000건씩 데이터 삽입
            if (!($i % 1000) && $i) {
                DB::table('parents')->insert($data);
                $data = []; // 배열 초기화
            }
        }

        // 남은 데이터 삽입
        if (!empty($data)) {
            DB::table('parents')->insert($data);
        }
    }
}
