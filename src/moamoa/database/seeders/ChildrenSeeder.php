<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\ParentModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
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
        //     ['parent_id' => 1, 'account' => 'guswls', 'password' => Hash::make('guswls'), 'name' => '배현진', 'email' => 'guswls@guswls.com', 'tel' => '01000000001',  'profile' => '/user-img/cute_girl_standing_and_smiling_character_cartoon_logo_hand_drawn_art_illustration.jpg',],
        //     ['parent_id' => 1, 'account' => 'gustjr', 'password' => Hash::make('gustjr'), 'name' => '김현석', 'email' => 'gustjr@gustjr.com', 'tel' => '01000000002',  'profile' => '/user-img/NicePng_tumblr-icon-png_1540854.png',],
        //     ['parent_id' => 1, 'account' => 'tkdals', 'password' => Hash::make('tkdals'), 'name' => '최상민', 'email' => 'tkdals@tkdals.com', 'tel' => '01000000003',  'profile' => '/user-img/JEMA_GER_1653-04.jpg',], 
        // ];
        // foreach($data as $item) {
        //     Child::create($item);
        // }
        $faker = Faker::create('ko_KR');

        $parents = ParentModel::where('parent_id', '>=', 2128)->where('parent_id', '<=', 2227)->get();
        foreach ($parents as $parent) {
            // 각 부모당 최소 1명, 최대 5명의 자녀 생성
            $childCount = rand(1, 5);
            
            for ($i = 0; $i < $childCount; $i++) {
                $data = [
                    'parent_id' => $parent->parent_id,
                    'account' => $faker->unique(true)->regexify('[a-z0-9]{6,20}'),
                    'password' => Hash::make('qwer1234'),
                    'name' => mb_substr($faker->name, 0, rand(2, 3)), // 최대 20글자
                    'email' => $faker->unique(true)->safeEmail(), // 최대 100글자
                    'tel' => '010' . $faker->numerify('########'), // 010으로 시작하고 나머지는 랜덤 숫자
                    'profile' => '/user-img/default.webp',
                ];
                
                Child::create($data);
            }
    
            // for ($i = 0; $i < 500; $i++) {
            //     $data = [
            //         'parent_id' => rand(2, 101),
            //         'account' => $faker->unique(true)->regexify('[a-z0-9]{6,20}'),
            //         'password' => Hash::make('qwer1234'),
            //         'name' => mb_substr($faker->name, 0, rand(2, 3)), // 최대 20글자
            //         'nick_name' => mb_substr($faker->optional()->realText(10, 1), 0, rand(2, 5)), 
            //         'email' => $faker->unique(true)->safeEmail(), // 최대 100글자
            //         'tel' => '010' . $faker->numerify('########'), // 010으로 시작하고 나머지는 랜덤 숫자
            //         'profile' => '/user-img/default.webp',
            //     ];
                
            //     Child::create($data);
            // }
        }
    }
}
