<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
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
    public function store(StoreBudgetRequest $request)
    {
        dd($request);
        try {
            $request['user_id'] = Auth::id();
            $validatedData = $request->validated();
            $budget = new Budget($validatedData);
            return redirect()->route('budgets.index');
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        try {
            BudgetController::destroy($budget);
            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
