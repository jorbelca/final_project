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
    protected $periodicity = ['unit', 'monthly', 'yearly', 'daily', 'weekly', 'minute', 'hourly', 'biweekly'];
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
            return CostViewController::notify("index", "Usuario inactivo", false);
        }
        return Inertia::render('Costs/CreateCosts');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create', Cost::class)) {
            return CostViewController::notify("create", "Usuario inactivo", false);
        }
        try {
            $user = Auth::user();

            if (!$user) {
                return back()->with(['error', 'No hay usuario autenticado']);
            }
            $request->merge(['user_id' => Auth::id()]);
            // Validar los datos
            $validated = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'description' => 'required|string|max:255',
                'cost' => 'required|numeric|min:0',
                'unit' => 'required|string|min:0|max:50',
                'periodicity' => 'required|in:' . implode(',', $this->periodicity),
            ]);;

            $newCost = new Cost($validated);

            $newCost->save();

            return CostViewController::notify("index", "Coste creado");
        } catch (\Throwable $th) {
            CostViewController::notify("create", "Error guardando el coste", false);
            throw new \Exception("Error saving the cost", 0, $th);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cost $cost)
    {
        if (!Gate::allows('update', $cost)) {
            return CostViewController::notify("index", "Usuario Inactivo", false);
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
                'periodicity' => 'required|in:' . implode(',', $this->periodicity),
            ]);

            $cost->update($validated);

            return CostViewController::notify("index", "Coste actualizado");
        } catch (\Throwable $th) {

            CostViewController::notify("index", "Error eliminando el coste", false);
            throw new \Exception("Error actualizando el coste", 0, $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cost $cost)

    {
        if (!Gate::allows('delete', $cost)) {
            return CostViewController::notify("index", "Usuario Inactivo", false);
        }

        try {
            CostController::destroy($cost);
            CostViewController::notify("index", "Eliminado");
        } catch (\Throwable $th) {
            CostViewController::notify("index", "Error eliminando el coste", false);
            throw new \Exception("Error eliminando el coste", 0, $th);
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
            return CostViewController::notify("index", "Usuario Inactivo", false);
        }
        return Inertia::render('Costs/ParseCost');
    }


    /**
     * Store costs from a file
     */
    public function storeMultiple(Request $request)
    {
        if (!Gate::allows('create', Cost::class)) {
            return CostViewController::notify("create", "Usuario Inactivo", false);
        }

        $user = Auth::user();

        if (!$user) {
            return back()->with(['error', 'No hay usuario autenticado']);
        }

        $costs = $request->input('costs', []);

        if (empty($costs)) {
            return CostViewController::notify("parse", "No hay costes", false);
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
                    'periodicity' => 'required|in:' . implode(',', $this->periodicity),
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
            return CostViewController::notify("index", count($allCosts) . " costes se han guardado correctamente");
        } catch (\Throwable $th) {
            DB::rollBack();

            throw new \Exception("Error saving the costs", 0, $th);
            return CostViewController::notify("parse", "Error guardando los costes: " . $th->getMessage(), false);
        }
    }
}
