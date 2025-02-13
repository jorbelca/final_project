<?php

namespace App\Http\Controllers;

use App\Models\Incidencie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class IncidenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        $user = Auth::user();

        if ($user->admin) {
            $tickets = Incidencie::with(['questioner', 'answerer'])->get();
            return Inertia::render('Incidencies', ['tickets' => $tickets]);
        }
        $tickets = Incidencie::with(['questioner', 'answerer'])->get();


        return Inertia::render('Incidencies', ['tickets' => $tickets]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            $request->merge(['questioner_id' => $user->id]);
            // Validar los datos

            $validated = $request->validate([
                'questioner_id' => 'required|integer|exists:users,id',
                'question' => 'required|string|max:255',
            ]);;

            $new_incidencie = new Incidencie($validated);
            $new_incidencie->save();

            return IncidenciesController::notify("index", "Incidencie created");
        } catch (\Throwable $th) {
            $errorMsg = $th->getMessage();

            return IncidenciesController::notify("index", "Error creating the incidencie, $errorMsg", false);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incidencie $incidency)
    {
        $user = Auth::user();
        if (!Gate::allows('update', $incidency)) {
            return IncidenciesController::notify("index", "You cannot answer this", false);
        }

        try {
            $data = [
                'answerer_id' => $user->id,
                'answer' => $request->input('answer'),
                'response_date' => now(),
            ];

            // Validar los datos
            $validated = Validator::make($data, [
                'answerer_id' => 'required|integer|exists:users,id',
                'answer' => 'required|string|max:255',
                'response_date' => 'required|date',
            ])->validate();

            $incidency->update($validated);


            if (!$incidency->save()) {
                return IncidenciesController::notify("index", "Error updating the incidencie", false);
            }

            return IncidenciesController::notify("index", "Incidencie updated");
        } catch (\Throwable $th) {
            $errorMsg = $th->getMessage();

            return IncidenciesController::notify("index", "Error updating the incidencie, $errorMsg", false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incidencie $incidency)
    {
        if (!Gate::allows('delete', $incidency)) {
            return IncidenciesController::notify("index", "You cannot delete this incidency", false);
        }

        try {
            $incidency->delete();
            IncidenciesController::notify("index", "Deleted");
        } catch (\Throwable $th) {
            return IncidenciesController::notify("index", "Error deleting the incidency", false);
        }
    }


    public function notify(String $sub_route, String $message, bool $success = true): RedirectResponse
    {
        if (!$success) {
            return redirect()->route('incidencies.' . $sub_route)->with([
                'flash' => [
                    'banner' => $message,
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return redirect()->route('incidencies.' . $sub_route)->with([
            'flash' => [
                'banner' => $message,
                'bannerStyle' => 'success',
            ]
        ]);
    }
}
