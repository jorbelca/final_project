<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscriptions>
 */
class PromptFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'prompt' => $this->faker->sentence,
        ];
    }
}
