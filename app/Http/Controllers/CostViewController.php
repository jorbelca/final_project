<?php

namespace App\Http\Controllers;


use App\Models\Cost;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
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

        // Obtener los costs asociados al usuario
        $costs = $user->costs;

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
        foreach ($costs as $cost) {
            try {
                $cost['user_id'] = Auth::id();
                // Validar los datos
                $validated = Validator::make($cost, [
                    'user_id' => 'required|integer|exists:users,id',
                    'description' => 'required|string|max:255',
                    'cost' => 'required|numeric|min:0',
                    'unit' => 'required|string|min:0|max:50',
                    'periodicity' => 'required|in:unit,daily,monthly,yearly,weekly'
                ])->validate();

                Cost::create($validated);
            } catch (\Throwable $th) {
                $errorMsg = $th->getMessage();
                return CostViewController::notify("parse", "Error Saving the file: $errorMsg ", false);
            }
        }
        return CostViewController::notify("index", "Costs saved");
    }
}
