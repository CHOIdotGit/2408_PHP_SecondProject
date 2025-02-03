<?php

namespace Database\Seeders;

use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SavingDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $saving = SavingSignUp::where('saving_sign_up_id', '>=', 1)->where('saving_sign_up_id', '<=', 3)->get();

        $faker = Faker::create('ko_KR');
        $data = [
            ['saving_sign_up_id' => $saving->saving_sign_up_id, 'deposit_at' => , 'category' => '0', 'principal' => ], 
            
        ];

        // saving_id에 따라 start_at과 end_at을 설정
        foreach ($data as &$item) {
            // saving_id에 따른 기간 계산 (7일 * saving_id)
            $savingPeriod = 7 * $item['saving_id'];

            // start_at을 기준으로 end_at 계산
            $item['end_at'] = (new \DateTime($item['start_at']))->modify("+$savingPeriod days")->format('Y-m-d');
        }

        foreach ($data as $row) {
            SavingDetail::create($row);
        }
    }
}
