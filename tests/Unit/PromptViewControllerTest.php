<?php

namespace Tests\Unit;

use App\Models\Prompt;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PromptViewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_correct_view_for_active_user()
    {
        $user = User::factory()->create(['active' => true]);
        $user->subscription()->create(['credits' => 10]);
        $user->prompt()->create(['prompt' => 'Texto']);

        $this->actingAs($user);

        $response = $this->get(route('prompt.index'));

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('IA/generateWithIA')
                ->has('prompt', fn($prompt) => $prompt['id'] === $user->prompt->id)
                ->has('credits', 10)
        );
    }

    public function test_index_redirects_for_inactive_user()
    {
        $user = User::factory()->create(['active' => false]);
        $this->actingAs($user);

        $response = $this->get(route('prompt.index'));

        $response->assertRedirect();
        $response->assertSessionHas('flash.banner', 'Inactive User');
    }

    public function test_store_creates_or_updates_prompt_and_generates_response()
    {
        // Crear usuario con una suscripción que tenga créditos
        $user = User::factory()->create(['active' => true]);
        $user->subscription()->create(['credits' => 1]);

        $this->actingAs($user);

        $data = [
            'prompt' => 'Test prompt',
            'additioNalPrompt' => 'Additional test prompt',
        ];

        $response = $this->post(route('prompt.store'), $data);

        $this->assertDatabaseHas('prompts', [
            'user_id' => $user->id,
            'prompt' => $data['additioNalPrompt'],
        ]);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Budgets/EditBudget')
                ->has('IA', true)
                ->has('clone', false)
        );
    }

    public function test_store_returns_error_if_no_credits()
    {
        // Crear usuario con una suscripción que NO tenga créditos
        $user = User::factory()->create(['active' => true]);
        $user->subscription()->create(['credits' => 0]);

        $this->actingAs($user);

        $data = [
            'prompt' => 'Test prompt',
            'additioNalPrompt' => 'Additional test prompt',
        ];

        $response = $this->post(route('prompt.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('flash.banner', 'No Credits ');
    }

    public function test_update_updates_prompt()
    {
        $user = User::factory()->create(['active' => true]);
        $user->subscription()->create(['credits' => 1]);
        $prompt = Prompt::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $data = ['prompt' => 'Updated prompt'];

        $response = $this->put(route('prompt.update', $prompt), $data);

        $this->assertDatabaseHas('prompts', [
            'id' => $prompt->id,
            'prompt' => $data['prompt'],
        ]);

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('IA/generateWithIA')
                ->has('prompt', fn($p) => $p['id'] === $prompt->id && $p['prompt'] === $data['prompt'])
        );
    }
}
