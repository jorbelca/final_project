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

            return SupportController::notify("index", "Incidency created");
        } catch (\Throwable $th) {
            $errorMsg = $th->getMessage();

            return SupportController::notify("index", "Error creating the incidency, $errorMsg", false);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Support $support)
    {
        $user = Auth::user();
        if (!Gate::allows('update', $support)) {
            return SupportController::notify("index", "You cannot answer this", false);
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
                return SupportController::notify("index", "Error updating the incidency", false);
            }

            return SupportController::notify("index", "Incidency updated");
        } catch (\Throwable $th) {
            $errorMsg = $th->getMessage();

            return SupportController::notify("index", "Error updating the incidency, $errorMsg", false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Support $support)
    {
        if (!Gate::allows('delete', $support)) {
            return SupportController::notify("index", "You cannot delete this question", false);
        }

        try {
            $support->delete();
            SupportController::notify("index", "Deleted");
        } catch (\Throwable $th) {
            return SupportController::notify("index", "Error deleting the incidency", false);
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
