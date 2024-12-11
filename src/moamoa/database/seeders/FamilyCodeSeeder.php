<?php

namespace Database\Seeders;

use App\Models\FamilyCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamilyCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['family_code' => 'ab123456'],
        ];
        foreach($data as $item) {
            FamilyCode::create($item);
        }
    }
}
