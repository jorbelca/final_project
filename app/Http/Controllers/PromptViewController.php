<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Client;
use App\Models\Prompt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;


class PromptViewController extends Controller
{
    protected $apiKey;
    protected $endpoint;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isActive()) {
            return BudgetViewController::notify("index", "Inactive User", false);
        }
        return Inertia::render(
            'IA/generateWithIA',
            [
                'prompt' => $user->prompt,
                'credits' => $user->subscription->credits ?? 0,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user->hasCredits()) {
                return PromptViewController::notify("No Credits ", false);
            }

            $request->validate([
                'additioNalPrompt' => 'string|max:40000',
                'prompt' => 'required|string|max:4000',
            ]);

            $additioNalPrompt = $request->input('additioNalPrompt');

            $prompt = Prompt::where('user_id', $user->id)->first();
            if (!$prompt && $additioNalPrompt) {
                $prompt = Prompt::create([
                    'user_id' => Auth::id(),
                    'prompt' => $request->input('additioNalPrompt'),
                ]);
            } else {
                $prompt->update([
                    'prompt' => $request->input('additioNalPrompt'),
                ]);
            }

            $response = $this->generateWithIA($prompt->prompt, $request->input('prompt'), $user);

            if ($response->status === true) {
                return Inertia::render('Budgets/EditBudget', [
                    'IA' => true,
                    'clone' => false,
                    'clients' => Auth::user()->clients,
                    'costs' => Auth::user()->costs,
                    'budget' => $response->budget,
                ]);
            }
        } catch (\Throwable $th) {
            dd($th);
            return PromptViewController::notify("Error generating the prompt", false);
        }
    }

    public function update(Request $request, Prompt $prompt)
    {
        $request->validate([
            'prompt' => 'required|string|max:40000',
        ]);

        $prompt->update([
            'prompt' => $request->input('prompt'),
        ]);

        return Inertia::render(
            'IA/generateWithIA',
            [
                'prompt' => $prompt,
            ]
        );
    }


    public function generateWithIA(String $additionalPrompt, String $prompt, User $user)
    {
        $costs = CostController::getUserCostsString($user);

        $rawPrompt = file_get_contents(resource_path('/prompts/budget_prompt.txt'));
        $cached_prompt = str_replace(
            ['{{user_prompt}}', '{{costs}}', '{{context}}'],
            [$prompt, $costs, $additionalPrompt],
            $rawPrompt
        );


        try {
            if (+$user->subscription->credits <= 0) {
                return PromptViewController::notify("You don't have enough credits", false);
            }

            // Llamar al endpoint de IA
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $cached_prompt],
                ],
            ]);

            $response = $result->choices[0]->message->content;

            // Try to clean up and fix potentially malformed JSON
            $response = preg_replace('/```json|```/', '', $response); // Remove markdown code blocks if present
            $response = preg_replace('/(\s*"notas"\s*:\s*"[^"]*?)(?:\s*▶\s*)?$/i', '$1"}', $response); // Fix truncated JSON

            $responseData = json_decode($response, true);

            // Check if JSON parsing was successful
            if (!$responseData) {
                // If still failing, try a more aggressive approach
                if (preg_match('/"descuento".*?"content"\s*:\s*\[(.*?)\].*?"notas"\s*:\s*"(.*?)"(?:.*?"client"\s*:\s*"(.*?)")?/s', $response, $matches)) {
                    $responseData = [
                        'descuento' => $matches[1] ?? 0,
                        'content' => json_decode('[' . $matches[1] . ']', true) ?: [],
                        'notas' => $matches[2] ?? '',
                        'client' => isset($matches[3]) ? $matches[3] : null,
                    ];
                } else {
                    dd($response, $responseData);
                    return PromptViewController::notify("Error parsing AI response", false);
                }
            }

            // Subtract one credit from user
            $user->subscription->decrement('credits');
            // Try to find client by name from the AI response
            $clientPrompted = null;
            if (!empty($responseData['client'])) {
                // Find client ID from clients table and check the relationship in client_user
                $client = Client::where(function ($query) use ($responseData) {
                    $query->where('name', 'like', '%' . $responseData['client'] . '%')
                        ->orWhere('name', 'like', '%' . 'empresa' . '%');
                })
                    ->whereHas('users', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    })
                    ->first();

                // If no exact match, try a more flexible search
                if (!$client) {
                    $client = Client::where(function ($query) use ($responseData) {
                        $query->where('name', 'like', '%' . substr($responseData['client'], 0, 10) . '%')
                            ->orWhere('name', 'like', '%' . 'empresa' . '%');
                    })
                        ->whereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        })
                        ->first();
                }

                if ($client) {
                    $clientPrompted = $client->id;
                }
            }

            // Create a budget object from AI response
            $budget = new Budget([
                'user_id' => $user->id,
                'client_id' => $clientPrompted ?? null,
                'content' => $responseData['content'] ?? [],
                'state' => 'draft',
                'discount' => $responseData['descuento'] ?? 0,
                'taxes' => $user->default_taxes,
                'notes' => $responseData['notas'] ?? '',
            ]);


            return (object)[
                'status' => true,
                'budget' => $budget,
            ];
        } catch (\Throwable $th) {
            dd($th);
            return PromptViewController::notify("Error generating the prompt", false);
        }
    }


    public static function notify(String $message, bool $success = true): RedirectResponse
    {
        $route = 'prompt.index';
        if (!$success) {
            return redirect()->route($route)->with([
                'flash' => [
                    'banner' => $message,
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return redirect()->route($route)->with([
            'flash' => [
                'banner' => $message,
                'bannerStyle' => 'success',
            ]
        ]);
    }
}
