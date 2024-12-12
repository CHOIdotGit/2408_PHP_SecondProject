<?php

namespace Database\Seeders;

use App\Models\Child;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        $data = [
            ['parent_id' => 1, 'account' => 'guswls', 'password' => Hash::make('guswls'), 'name' => '배현진', 'nick_name' => '공주', 'email' => 'guswls@guswls.com', 'tel' => '01000000001',  'profile' => '/profile/cute_girl_standing_and_smiling_character_cartoon_logo_hand_drawn_art_illustration.jpg',],
            ['parent_id' => 1, 'account' => 'gustjr', 'password' => Hash::make('gustjr'), 'name' => '김현석', 'nick_name' => 'qwe123', 'email' => 'gustjr@gustjr.com', 'tel' => '01000000002',  'profile' => '/profile/NicePng_tumblr-icon-png_1540854.png',],
            ['parent_id' => 1, 'account' => 'tkdals', 'password' => Hash::make('tkdals'), 'name' => '최상민', 'nick_name' => '뚤기', 'email' => 'tkdals@tkdals.com', 'tel' => '01000000003',  'profile' => '/profile/JEMA_GER_1653-04.jpg',], 
        ];
        foreach($data as $item) {
            Child::create($item);
        }
    }
}
