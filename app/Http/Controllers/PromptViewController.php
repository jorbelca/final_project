<?php

namespace App\Http\Controllers;

use App\Models\Budget;
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
        return Inertia::render(
            'IA/generateWithIA',
            [
                'prompt' => $user->prompt,
                'credits' => $user->subscription->credits,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $prompts = Prompt::where('user_id', $user->id)->get();
        return Inertia::render('IA/generateWithIA', compact('prompts'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {

            $user = Auth::user();
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
                    'clone' => true,
                    'clients' => Auth::user()->clients,
                    'costs' => Auth::user()->costs,
                    'budget' => $response->budget,
                    'notas' => $response->notas,
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

        $cached_prompt = "Eres un consultor experto en diversos ambitos .
        Quiero que generes el contenido de un presupuesto profesional en base a una peticion del usuario que mas adelante se te proporcionara  .
          El contenido del presupuesto debe estar basado
         en unos costes de produccion del usuario que se te pasaran mas adelante. Cada coste incluye una descripción,
         un costo unitario y una temporalidad . El formato de la respuesta debe ser un JSON que debe contener exactamente
        {descuento: descuento si el usuario cita alguno en el prompt,
         content: (contenido del presupuesto, sin notas) {description: Mantenimiento,cost: 50,quantity: 1},
         notas: notas que consideres y que den un contexto y explicacion a las decisiones tomadas para generar el presupuesto}.
          Debes tener en cuenta la complejidad de la tarea y si hay elementos que no estan incluidos en los costes de produccion, incluirlos
            en el presupuesto. El presupuesto debe ser claro y conciso, y puede incluir algun tipo de explicacion en el apartado notas.
            Quiero que presupuestes en base a este prompt:$prompt.
            Tengo estos costes de produccion: $costs.
            Usa este contexto adicional proporcionado por el usuario: $additionalPrompt.
          ";


        try {
            if ($user->subscription->credits <= 0) {
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
                if (preg_match('/"descuento".*?"content"\s*:\s*\[(.*?)\].*?"notas"\s*:\s*"(.*?)(?:"|$)/s', $response, $matches)) {
                    $responseData = [
                        'descuento' => null,
                        'content' => json_decode('[' . $matches[1] . ']', true) ?: [],
                        'notas' => $matches[2] ?? ''
                    ];
                } else {
                    dd($response, $responseData);
                    return PromptViewController::notify("Error parsing AI response", false);
                }
            }

            // Subtract one credit from user
            $user->subscription->decrement('credits');


            // Create a budget object from AI response
            $budget = new Budget([
                'user_id' => $user->id,
                'client_id' => null,
                'content' => $responseData['content'] ?? [],
                'state' => 'draft',
                'discount' => $responseData['descuento'] ?? 0,
                'taxes' => $user->default_taxes
            ]);


            return (object)[
                'status' => true,
                'budget' => $budget,
                'notas' => $responseData['notas'] ?? '',
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
