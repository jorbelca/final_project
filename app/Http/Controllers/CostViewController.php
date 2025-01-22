<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCostRequest;
use App\Models\Cost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CostViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener los costs asociados al usuario
        $costs = $user->costs;

        return Inertia::render('Costs', [
            'costs' => $costs,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CreateCosts');
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
            // Validar los datos
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'description' => 'required|string|max:255',
                'cost' => 'required|numeric',
                'unit' => 'required|string|max:50',
                'periodicity' => 'required|in:unit,daily,monthly,yearly,weekly'
            ]);;

            $newCost = new Cost($validated);

            $newCost->save();

            return response()->json(['messsage' => "New Cost Created", $newCost], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred', dd($th)], 500); // Otros errores
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cost $cost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cost $cost)
    {
        return Inertia::render('EditCost', [
            'cost' => $cost
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cost $cost)
    {
        try {
            // Validar los datos
            $validated = $request->validate([
                'description' => 'required|string|max:255',
                'cost' => 'required|numeric',
                'unit' => 'required|string|max:50',
                'periodicity' => 'required|in:unit,daily,monthly,yearly,weekly'
            ]);

            $cost->update($validated);

            return redirect()->route('costs.index')->with('success', 'Cost updated');
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cost $cost)
    {
        try {
            CostController::destroy($cost);
            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
