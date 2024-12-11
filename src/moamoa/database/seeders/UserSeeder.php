<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['code_id' => '1', 'account' => 'wndus', 'password' => Hash::make('wndus'), 'auth' => '0', 'name' => '김주연', 'nick_name' => '춘식이', 'gender' => '1', 'email' => 'wndus@wndus.com', 'tel' => '01000000000',  'profile' => '/profile/cnstlrdl.webp'],
            ['code_id' => '1', 'account' => 'guswls', 'password' => Hash::make('guswls'), 'auth' => '1', 'name' => '배현진', 'nick_name' => '공주', 'gender' => '1', 'email' => 'guswls@guswls.com', 'tel' => '01000000001',  'profile' => '/profile/cute_girl_standing_and_smiling_character_cartoon_logo_hand_drawn_art_illustration.jpg'],
            ['code_id' => '1', 'account' => 'gustjr', 'password' => Hash::make('gustjr'), 'auth' => '1', 'name' => '김현석', 'nick_name' => 'qwe123', 'gender' => '0', 'email' => 'gustjr@gustjr.com', 'tel' => '01000000002',  'profile' => '/profile/NicePng_tumblr-icon-png_1540854.png'],
            ['code_id' => '1', 'account' => 'tkdals', 'password' => Hash::make('tkdals'), 'auth' => '1', 'name' => '최상민', 'nick_name' => '뚤기', 'gender' => '0', 'email' => 'tkdals@tkdals.com', 'tel' => '01000000003',  'profile' => '/profile/JEMA_GER_1653-04.jpg'],
        ];
        foreach($data as $item) {
            User::create($item);
        }
    }
}
