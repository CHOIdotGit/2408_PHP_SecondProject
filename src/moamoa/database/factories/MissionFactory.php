<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Mission::select('mission_id')->inRandomOrder()->first();
        return [
            'parent_id' => 1,
            'child_id' => rand(2,4),
            'ms_id' => rand(1,4),
            'title' => $this->faker->text(50),
            'content' => $this->faker->text(255),
            'amount' => rand(100,10000),
            'status' => rand(1,5),
            'start_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
