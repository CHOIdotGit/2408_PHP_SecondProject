<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['in_type' => '0', 'in_name' => '식비', 'in_img' => '/img/icon-bus.png'],
            ['in_type' => '1', 'in_name' => '교통비', 'in_img' => '/img/icon-fastfood.png'],
            ['in_type' => '2', 'in_name' => '쇼핑', 'in_img' => '/img/icon-shoppingbag.png'],
            ['in_type' => '3', 'in_name' => '기타', 'in_img' => '/img/Pngtreestationery_icon_3728043.png'],
        ];
        foreach($data as $item) {
            User::create($item);
        }
    }
}
