<?php

namespace Database\Seeders;

use App\Models\ParentModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
            ['account' => 'wndus', 'password' => Hash::make('wndus'), 'name' => '김주연', 'nick_name' => '춘식이', 'email' => 'wndus@wndus.com', 'tel' => '01000000000', 'profile' => '/profile/cnstlrdl.webp', 'family_code' => 'ab123456'],
        ];
        foreach($data as $item) {
            ParentModel::create($item);
        }
    }
}
