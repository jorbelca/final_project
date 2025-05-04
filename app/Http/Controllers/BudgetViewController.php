<?php

namespace App\Http\Controllers;


use App\Models\Budget;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class BudgetViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 4); // Default to 4 if not provided
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener los clientes asociados al usuario
        $budgets = $user->budgets()
            ->orderBy('created_at', 'desc')
            ->with(['client:id,name,image_url']) // Carga solo los campos deseados
            ->paginate($size);

        $budgetCount = $user->budgets()
            ->selectRaw("state, COUNT(*) as count")
            ->groupBy('state')
            ->pluck('count', 'state');

        return Inertia::render('Budgets/Budgets', [
            'budgets' => $budgets,
            'budgetCount' => $budgetCount
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('view', new Budget())) {
            return BudgetViewController::notify("index", "Usuario Inactivo", false);
        }



        return Inertia::render("Budgets/CreateBudget", [
            'clients' => Auth::user()->clients,
            'costs' => Auth::user()->costs,
            'taxes' => Auth::user()->default_taxes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create', Budget::class)) {
            return BudgetViewController::notify("index", "Usuario Inactivo", false);
        }


        try {
            // Obtener el usuario autenticado
            /** @var User $user */
            $user = Auth::user();
            if (!$user) {
                return redirect()->back()->with('error', 'No se pudo obtener el usuario autenticado');
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
                'taxes' => 'required|integer',
                'notes' => 'sometimes|string|max:10000|nullable',
            ]);
            // Sanitize the 'notes' field
            if (isset($validated['notes'])) {
                $validated['notes'] = strip_tags($validated['notes']);
            }

            $validated['content'] = json_encode($validated['content']);


            $budget = new Budget($validated);
            $budget->save();

            if ($budget) {
                return BudgetViewController::notify("index", "Presupuesto guardado");
            }
        } catch (\Throwable $th) {
            BudgetViewController::notify("index", "Error guardando el presupuesto", false);
            throw new Exception("Error guardando el presupuesto", 0, $th);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        if (!Gate::allows('delete', $budget)) {
            return BudgetViewController::notify("index", "Usuario Inactivo", false);
        }

        return Inertia::render('Budgets/EditBudget', [
            'budget' => $budget,
            'clients' => Auth::user()->clients,
            'costs' => Auth::user()->costs,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function clone(String $id)
    {
        $budget = Budget::findOrFail($id);
        if (!Gate::allows('delete', $budget)) {
            return BudgetViewController::notify("index", "Usuario Inactivo", false);
        }
        return Inertia::render('Budgets/EditBudget', [
            'clone' => true,
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
        if (!Gate::allows('delete', $budget)) {
            return BudgetViewController::notify("index", "Usuario Inactivo", false);
        }


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
                'taxes' => 'required|integer',
                'notes' => 'sometimes|string|max:10000|nullable',
            ]);

            // Sanitize the 'notes' field
            if (isset($validated['notes'])) {
                $validated['notes'] = strip_tags($validated['notes']);
            }

            $validated['content'] = json_encode($validated['content']);


            $budget->update($validated);

            if ($budget) {
                return BudgetViewController::notify("index", "Presupuesto actualizado");
            }
        } catch (\Throwable $th) {
            BudgetViewController::notify("index", "Error actualizando el presupuesto", false);
            throw new Exception("Error actualizando el presupuesto", 0, $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        if (!Gate::allows('delete', $budget)) {
            return BudgetViewController::notify("index", "Usuario inactivo", false);
        }
        try {
            BudgetController::destroy($budget);
            return BudgetViewController::notify("index", "Presupuesto eliminado");
        } catch (\Throwable $th) {
            BudgetViewController::notify("index", "Error eliminando el presupuesto", false);
            throw new Exception("Error deleting the budget", 0, $th);
        }
    }


    public static function notify(String $sub_route, String $message, bool $success = true): RedirectResponse
    {
        if (!$success) {
            return redirect()->route('budgets.' . $sub_route)->with([
                'flash' => [
                    'banner' => $message,
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return redirect()->route('budgets.' . $sub_route)->with([
            'flash' => [
                'banner' => $message,
                'bannerStyle' => 'success',
            ]
        ]);
    }
}
