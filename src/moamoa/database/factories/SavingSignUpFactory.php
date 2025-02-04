<?php

namespace Database\Factories;

use App\Models\Child;
use App\Models\SavingSignUp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SavingSignUp>
 */
class SavingSignUpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $child = Child::whereBetween('child_id', [1, 2, 3])->inRandomOrder()->value('child_id');
        // $child = Child::whereBetween('child_id', [1, 2, 3])->get();
        return [
                // 'child_id' => $child,
                'saving_product_id' => rand(1, 7), 
                'saving_sign_up_deposit_at' => rand(0,7), 
                'saving_sign_up_start_at' => $this->faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d'), 
                'saving_sign_up_end_at' => $this->faker->dateTimeBetween('-2 month', 'now')->format('Y-m-d'), 
                'saving_sign_up_status' => rand(0,2), 
        ];   
    
        // // saving_product_id 따라 saving_sign_up_start_at, saving_sign_up_end_at 설정
        // foreach ($data as &$item) {
        //     // saving_product_id 따른 기간 계산 (7일 * saving_product_id)
        //     $savingPeriod = 7 * $item['saving_product_id'];

        //     // saving_sign_up_start_at 기준으로 saving_sign_up_end_at 계산
        //     $item['saving_sign_up_end_at'] = (new \DateTime($item['saving_sign_up_start_at']))->modify("+$savingPeriod days")->format('Y-m-d');
        // }

        // foreach ($data as $row) {
        //     SavingSignUp::create($row);
        // }
    }
}
