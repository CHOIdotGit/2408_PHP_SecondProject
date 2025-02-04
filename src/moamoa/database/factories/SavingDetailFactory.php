<?php

namespace Database\Factories;

use App\Models\SavingDetail;
use App\Models\SavingSignUp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SavingDetail>
 */
class SavingDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $saving = SavingSignUp::pluck('saving_sign_up_id');
        return [
            'saving_sign_up_id' => $saving, 
            'saving_detail_category' => rand(0,1), 
            'saving_detail_left' => rand(0,1000), 
            'saving_detail_income' => rand(0,1000),
            'saving_detail_outcome' => 0,
        ];
        // $savingSignUpIds = SavingSignUp::pluck('saving_sign_up_id');
        // $details = [];

        // foreach ($savingSignUpIds as $id) {
        //     $details[] = [
        //         'saving_sign_up_id' => $id,
        //         'saving_detail_category' => rand(0, 1), 
        //         'saving_detail_left' => rand(0, 1000), 
        //         'saving_detail_income' => rand(0, 1000),
        //         'saving_detail_outcome' => 0,
        //     ];
        // }

        // return $details;
    }
}
