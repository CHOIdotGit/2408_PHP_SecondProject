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
        // $childId = Child::whereNotIn('child_id', [1, 2, 3])->inRandomOrder()->value('child_id');
        $childId = Child::whereBetween('child_id', [26, 305])->inRandomOrder()->value('child_id');

        return [
            // 'child_id' => $childId = Child::inRandomOrder()->value('child_id'),
            'child_id' => $childId,
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
