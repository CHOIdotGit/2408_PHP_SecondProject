<?php

namespace Database\Factories;

use App\Models\Income;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class IncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = Income::select('income_id')->inRandomOrder()->first();
        return [
            'parent_id' => 1,
            'child_id' => rand(2,4),
            'in_id' => rand(0,3),
            'income_code' => rand(0,1),
            'amount' => rand(100,10000),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'memo' => null,
        ];
    }
}
