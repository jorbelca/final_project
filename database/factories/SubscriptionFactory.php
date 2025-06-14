<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscriptions>
 */
class SubscriptionFactory extends Factory
{
    protected $primaryKey = 'subscription_id';
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'plan_id' => Plan::inRandomOrder()->first()?->id,
            'payment_number' => fake()->bankAccountNumber(),
            'active' => fake()->boolean(),
            'start_date' => now(),
        ];
    }
}
