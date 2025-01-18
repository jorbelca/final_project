<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCostRequest;
use App\Http\Requests\UpdateCostRequest;
use App\Models\Cost;
use Illuminate\Support\Facades\Log;

class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cost::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json("Save an Expense", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCostRequest $request)
    {
        try {
            // Validar
            $validated = $request->validated();

            $newCost = new Cost($validated);

            $newCost->save();

            return response()->json(['messsage' => "New Cost Created", $newCost], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500); // Otros errores
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cost $cost)
    {
        try {
            return $cost;
        } catch (\Throwable $th) {

            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cost $cost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCostRequest $request, Cost $cost)
    {
        try {
            $validated = $request->validated();

            $cost->update($validated);

            return response()->json(['message' => 'Cost Updated ', "costUpdated" => $cost], 200);
        } catch (\Throwable $th) {

            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    static public function destroy(Cost $cost)
    {
        try {

            $cost->delete();

            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {
            // Para cualquier otro error
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
