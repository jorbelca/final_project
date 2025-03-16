<?php

namespace App\Http\Controllers;


use App\Models\Cost;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CostViewController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener los costs asociados al usuario, menos updated_at
        $costs = $user->costs->makeHidden(['updated_at']);

        return Inertia::render('Costs/Costs', [
            'costs' => $costs,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response| RedirectResponse
    {

        if (!Gate::allows('view', new Cost())) {
            return CostViewController::notify("index", "Inactive User", false);
        }
        return Inertia::render('Costs/CreateCosts');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create', Cost::class)) {
            return CostViewController::notify("create", "Inactive User", false);
        }
        try {
            $user = Auth::user();

            if (!$user) {
                return back()->with(['error', 'No authenticated user found.']);
            }
            $request->merge(['user_id' => Auth::id()]);
            // Validar los datos
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'description' => 'required|string|max:255',
                'cost' => 'required|numeric|min:0',
                'unit' => 'required|string|min:0|max:50',
                'periodicity' => 'required|in:unit,daily,monthly,yearly,weekly'
            ]);;

            $newCost = new Cost($validated);

            $newCost->save();

            return CostViewController::notify("index", "Cost created");
        } catch (\Throwable $th) {
            return CostViewController::notify("create", "Error Saving the cost", false);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cost $cost)
    {
        if (!Gate::allows('update', $cost)) {
            return CostViewController::notify("index", "Inactive User", false);
        }

        return Inertia::render('Costs/EditCost', [
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
                'cost' => 'required|numeric|min:0',
                'unit' => 'required|string|min:0|max:50',
                'periodicity' => 'required|in:unit,daily,monthly,yearly,weekly'
            ]);

            $cost->update($validated);

            return redirect()->route('costs.index')->with([
                'flash' => [
                    'banner' => 'Cost updated',
                    'bannerStyle' => 'success',
                ]
            ]);
        } catch (\Throwable $th) {
            return CostViewController::notify("index", "Error updating the cost", false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cost $cost)

    {
        if (!Gate::allows('delete', $cost)) {
            return CostViewController::notify("index", "Inactive User", false);
        }

        try {
            CostController::destroy($cost);
            CostViewController::notify("index", "Deleted");
        } catch (\Throwable $th) {
            return CostViewController::notify("index", "Error deleting the cost", false);
        }
    }



    public function notify(String $sub_route, String $message, bool $success = true): RedirectResponse
    {
        if (!$success) {
            return redirect()->route('costs.' . $sub_route)->with([
                'flash' => [
                    'banner' => $message,
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return redirect()->route('costs.' . $sub_route)->with([
            'flash' => [
                'banner' => $message,
                'bannerStyle' => 'success',
            ]
        ]);
    }


    public function parse(): Response| RedirectResponse
    {


        if (!Gate::allows('view', new Cost())) {
            return CostViewController::notify("index", "Inactive User", false);
        }
        return Inertia::render('Costs/ParseCost');
    }


    /**
     * Store costs from a file
     */
    public function storeMultiple(Request $request)
    {
        if (!Gate::allows('create', Cost::class)) {
            return CostViewController::notify("create", "Inactive User", false);
        }

        $user = Auth::user();

        if (!$user) {
            return back()->with(['error', 'No authenticated user found.']);
        }

        $costs = $request->input('costs', []);

        if (empty($costs)) {
            return CostViewController::notify("parse", "No costs uploaded", false);
        }

        // Preparar los datos para validación masiva
        $allCosts = [];
        $userId = Auth::id();
        $validator = Validator::make([], []); // Inicializar validador vacío
        $now = now();

        try {
            DB::beginTransaction();

            // Primera pasada: validar todos los registros
            foreach ($costs as $index => $cost) {
                $cost['user_id'] = $userId;

                // Validar cada registro
                $itemValidator = Validator::make($cost, [
                    'user_id' => 'required|integer|exists:users,id',
                    'description' => 'required|string|max:255',
                    'cost' => 'required|numeric|min:0',
                    'unit' => 'required|string|max:50',
                    'periodicity' => 'required|in:unit,daily,monthly,yearly,weekly'
                ]);

                if ($itemValidator->fails()) {
                    // Agregar errores al validador principal
                    foreach ($itemValidator->errors()->messages() as $key => $messages) {
                        foreach ($messages as $message) {
                            $validator->errors()->add("costs.{$index}.{$key}", $message);
                        }
                    }
                } else {
                    // Si es válido, guardarlo para inserción
                    $validated = $itemValidator->validated();
                    $validated['created_at'] = $now;
                    $validated['updated_at'] = $now;
                    $allCosts[] = $validated;
                }
            }

            // Si hay errores de validación, fallar
            if ($validator->errors()->isNotEmpty()) {
                throw new ValidationException($validator);
            }

            // Segunda pasada: insertar en lotes para máxima eficiencia
            $chunks = array_chunk($allCosts, 100); // Dividir en grupos de 100 para evitar problemas de rendimiento

            foreach ($chunks as $chunk) {
                Cost::insert($chunk);
            }

            DB::commit();
            return CostViewController::notify("index", count($allCosts) . " costs has been saved correctly");
        } catch (\Throwable $th) {
            DB::rollBack();

            return CostViewController::notify("parse", "Error saving the costs: " . $th->getMessage(), false);
        }
    }
}
