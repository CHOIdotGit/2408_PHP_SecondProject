<?php

namespace Database\Factories;

use App\Models\Child;
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
            'child_id' => $childId = Child::inRandomOrder()->value('child_id'),
            'parent_id' => Child::where('child_id', $childId)->value('parent_id'),
            'category' => rand(0, 3),
            'transaction_code' => '1',
            'title' => $this->faker->realText(10),
            'amount' => round(rand(100, 50000) / 100) * 100,
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'memo' => $this->faker->realText(50),
        ];
    }
}
