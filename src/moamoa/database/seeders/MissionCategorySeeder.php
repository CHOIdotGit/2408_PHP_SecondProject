<?php

namespace Database\Seeders;

use App\Models\MissionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['ms_type' => '0', 'ms_name' => '학습', 'ms_img' => 'icon-pencil.png'],
            ['ms_type' => '1', 'ms_name' => '취미', 'ms_img' => 'icon-bicycle.png'],
            ['ms_type' => '2', 'ms_name' => '집안일', 'ms_img' => 'icon-cleaner.png'],
            ['ms_type' => '3', 'ms_name' => '생활습관', 'ms_img' => 'icon-clock.png'],
            ['ms_type' => '4', 'ms_name' => '기타', 'ms_img' => 'icon-checklist7.png'],
        ];
        foreach($data as $item) {
            MissionCategory::create($item);
        }
    }
}
