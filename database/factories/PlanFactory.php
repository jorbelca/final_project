<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plans>
 */
class PlanFactory extends Factory
{
    protected $primaryKey = 'plan_id';
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 1000),
            'duration_in_days' => fake()->numberBetween(1, 365),
            'features' => json_encode([
                'description' => fake()->paragraph,
            ]),

        ];
    }
}
