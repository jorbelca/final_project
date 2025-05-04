<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        $user = Auth::user();

        if ($user->admin) {
            $tickets = Support::with(['questioner', 'answerer'])->get();
            return Inertia::render('Support/Support', ['tickets' => $tickets]);
        }
        $tickets = Support::with(['questioner', 'answerer'])->get();


        return Inertia::render('Support/Support', ['tickets' => $tickets]);
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
                'question' => 'required|string|max:20000',
            ]);;

            $new_incidency = new Support($validated);
            $new_incidency->save();

            return SupportController::notify("index", "Incidencia creada");
        } catch (\Throwable $th) {
            $errorMsg = $th->getMessage();

            SupportController::notify("index", "Error creando la incidencia, $errorMsg", false);
            throw new \Exception("Error creating the incidency", 0, $th);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Support $support)
    {
        $user = Auth::user();
        if (!Gate::allows('update', $support)) {
            return SupportController::notify("index", "No estas autorizado a responder", false);
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
                'answer' => 'required|string|max:20000',
                'response_date' => 'required|date',
            ])->validate();

            $support->update($validated);


            if (!$support->save()) {
                return SupportController::notify("index", "Error actualizando la incidencia", false);
            }

            return SupportController::notify("index", "Incidencia actualizada");
        } catch (\Throwable $th) {
            $errorMsg = $th->getMessage();

            SupportController::notify("index", "Error actualizando la incidencia, $errorMsg", false);
            throw new \Exception("Error actualizando la incidencia", 0, $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Support $support)
    {
        if (!Gate::allows('delete', $support)) {
            return SupportController::notify("index", "No puedes eliminar esta pregunda", false);
        }

        try {
            $support->delete();
            SupportController::notify("index", "Deleted");
        } catch (\Throwable $th) {
            SupportController::notify("index", "Error eliminando la incidencia", false);
            throw new \Exception("Error deleting the incidency", 0, $th);
        }
    }


    public function notify(String $sub_route, String $message, bool $success = true): RedirectResponse
    {
        if (!$success) {
            return redirect()->route('support.' . $sub_route)->with([
                'flash' => [
                    'banner' => $message,
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return redirect()->route('support.' . $sub_route)->with([
            'flash' => [
                'banner' => $message,
                'bannerStyle' => 'success',
            ]
        ]);
    }
}
