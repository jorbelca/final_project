<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Budget::all();
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    static public function store(StoreBudgetRequest $request)
    {
        dd($request);
        try {
            // Validar
            $validated = $request->validated();

            $newBudget = new Budget($validated);

            $newBudget->save();


            return response()->json(['messsage' => "New Budget Created", $newBudget], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Budget not found'], 404); // Respuesta clara si no se encuentra el presupuesto
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500); // Otros errores
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        try {
            return $budget;
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        try {

            // Validar

            $validated = $request->validated();

            $budget->update($validated);


            return response()->json(['messsage' => " Budget Updated", "updatedBudget" => $budget], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Budget not found'], 404); // Respuesta clara si no se encuentra el presupuesto
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500); // Otros errores
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    static public function destroy(Budget $budget)
    {
        try {
            $budget->delete();


            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {

            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
