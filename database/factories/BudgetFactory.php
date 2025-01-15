<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    protected $primaryKey = 'budget_id';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'client_id' => Client::inRandomOrder()->first()?->id,
            'content' => json_encode([
                [
                    "quantity" => 1,
                    "details" => [
                        'name' => 'Item 1',
                        'price' => fake()->randomFloat(2, 10, 500)
                    ]
                ],
                [
                    "quantity" => 1,
                    "details" => [
                        'name' => 'Item 2',
                        'price' => fake()->randomFloat(2, 10, 500)
                    ]
                ]
            ]), // Contenido JSON del presupuesto
            'state' => fake()->randomElement(['draft', 'approved', 'rejected']), // Estado del presupuesto
            'discount' => fake()->numberBetween(0, 70),
            'taxes' => fake()->numberBetween(1, 25),
        ];
    }
}
