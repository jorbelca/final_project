<?php

namespace Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Costs>
 */
class CostFactory extends Factory
{
    protected $primaryKey = 'cost_id';
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'description' => fake()->sentence(),
            'cost' => fake()->randomFloat(2, 1, 1000),
            'unit' => fake()->randomElement(['kg', 'm3', 'pieza', 'horas']),
            'periodicity' => fake()->randomElement(['unit', 'monthly', 'yearly','daily','weekly']),
        ];
    }
}
