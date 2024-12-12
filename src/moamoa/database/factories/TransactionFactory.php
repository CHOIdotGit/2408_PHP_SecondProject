<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'parent_id' => 1,
            'child_id' => rand(1,3),
            'category' => rand(0,3),
            'transaction_code' => rand(0,1),
            'title' => $this->faker->text(50),
            'amount' => round(rand(100,50000) / 100) * 100,
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'memo' => null,
        ];
    }
}
