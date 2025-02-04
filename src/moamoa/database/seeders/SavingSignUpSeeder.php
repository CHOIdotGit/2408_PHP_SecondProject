<?php

namespace Database\Seeders;

use App\Models\SavingSignUp;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SavingSignUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = new DateTime();
        $date2 = new DateTime();
        $date3 = new DateTime();
        $data = [
            ['child_id' => 1, 'saving_product_id' => 2, 'saving_sign_up_deposit_at' => 7, 'saving_sign_up_start_at' => $date->modify('-7 days')->format('Y-m-d'), 
                'saving_sign_up_end_at' => $date->modify('+13 days')->format('Y-m-d'), 
                'saving_sign_up_status' => 0]
            ,['child_id' => 2, 'saving_product_id' => 5, 'saving_sign_up_deposit_at' => 5, 'saving_sign_up_start_at' => $date2->modify('-15 days')->format('Y-m-d'), 
                'saving_sign_up_end_at' => $date2->modify('+34 days')->format('Y-m-d'), 
                'saving_sign_up_status' => 0]
            ,['child_id' => 3, 'saving_product_id' => 7, 'saving_sign_up_deposit_at' => 0, 'saving_sign_up_start_at' => $date3->modify('-34 days')->format('Y-m-d'), 
                'saving_sign_up_end_at' => $date3->modify('+48 days')->format('Y-m-d'), 
                'saving_sign_up_status' => 0]
        ];

        foreach($data as $item) {
            SavingSignUp::create($item);
        }
    }
}
