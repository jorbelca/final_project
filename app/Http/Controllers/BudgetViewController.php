<?php

namespace App\Http\Controllers;


use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BudgetViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener los clientes asociados al usuario
        $budgets = $user->budgets;
        //Vincular los presupuestos con los clientes
        $budgets->load('client');


        return Inertia::render('Budgets', [
            'budgets' => $budgets,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render("CreateBudget", [
            'clients' => Auth::user()->clients,
            'costs' => Auth::user()->costs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        try {
            // Obtener el usuario autenticado
            /** @var User $user */
            $user = Auth::user();
            if (!$user) {
                return redirect()->back()->with('error', 'No authenticated user found.');
            }
            $request->merge(['user_id' => Auth::id()]);

            $content = $request->input('content', []);

            // Transformar el array de arrays a un array de objetos
            $transformedContent = collect($content)->map(function ($item) {
                // Transformar cada array a un objeto
                return (object)[
                    'description' => isset($item['description']) ? (string) $item['description'] : '',
                    'cost' => isset($item['cost']) ? (float) $item['cost'] : 0,
                    'quantity' => isset($item['quantity']) ? (int) $item['quantity'] : 0,
                ];
            })->filter(function ($item) {
                // Validar que cada objeto tenga los datos correctos
                return !empty($item->description) &&
                    $item->cost > 0 &&
                    $item->quantity > 0;
            })->values()->toArray();

            $request->merge(['content' => $transformedContent]);
            $request->merge(['client_id' => $request->input('client_id') ?: null]);


            // Validar los datos

            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'client_id' => 'nullable|exists:clients,id',
                'content' => ["sometimes", "array"],
                'state' => 'sometimes|in:draft,approved,rejected',
                'discount' => 'sometimes|integer',
                'taxes' => 'required|integer'
            ]);
            $validated['content'] = json_encode($validated['content']);


            $budget = new Budget($validated);
            $budget->save();

            if ($budget) {
                return redirect()->route('budgets.index');
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error saving the budget: ' . $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        return Inertia::render('EditBudget', [
            'budget' => $budget,
            'clients' => Auth::user()->clients,
            'costs' => Auth::user()->costs,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {


        try {
            $content = $request->input('content', []);

            // Transformar el array de arrays a un array de objetos
            $transformedContent = collect($content)->map(function ($item) {
                // Transformar cada array a un objeto
                return (object)[
                    'description' => isset($item['description']) ? (string) $item['description'] : '',
                    'cost' => isset($item['cost']) ? (float) $item['cost'] : 0,
                    'quantity' => isset($item['quantity']) ? (int) $item['quantity'] : 0,
                ];
            })->filter(function ($item) {
                // Validar que cada objeto tenga los datos correctos
                return !empty($item->description) &&
                    $item->cost > 0 &&
                    $item->quantity > 0;
            })->values()->toArray();

            $request->merge(['content' => $transformedContent]);
            $request->merge([
                'client_id' => $request->input('client_id') === "null" ? null : $request->input('client_id'),
            ]);

            // Validar los datos

            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'client_id' => 'nullable|exists:clients,id',
                'content' => ["sometimes", "array"],
                'state' => 'sometimes|in:draft,approved,rejected',
                'discount' => 'sometimes|integer',
                'taxes' => 'required|integer'
            ]);
            $validated['content'] = json_encode($validated['content']);


            $budget->update($validated);

            if ($budget) {
                return redirect()->route('budgets.index');
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error updating the budget: ' . $th->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        try {
            BudgetController::destroy($budget);
            return redirect()->route('budgets.index');
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
