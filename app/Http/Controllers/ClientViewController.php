<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ClientViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener los clientes asociados al usuario
        $clients = $user->clients;

        return Inertia::render('Clients', [
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('view', new Client())) {
            return ClientViewController::notify("index", "Inactive User", false);
        }
        return Inertia::render('CreateClients');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if (!Gate::allows('create')) {
        //     return ClientViewController::notify("index", "Inactive User", false);
        // }
        try {
            // Obtener el usuario autenticado
            /** @var User $user */
            $user = Auth::user();
            if (!$user) {
                return redirect()->back()->with('error', 'No authenticated user found.');
            }
            $request->merge(['user_id' => Auth::id()]);
            // Validar los datos
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email',
                'company_name' => 'required|string|max:255',
                'image_url' => 'sometimes|url|max:255',
            ]);
            // Crear el cliente
            $newClient = Client::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'company_name' => $validated['company_name'],
                'image_url' => $validated['image_url'] ?? null,
            ]);;
            $user->clients()->attach($newClient->id);


            return ClientViewController::notify("index", "Client created succesfully");
        } catch (\Throwable $th) {
            return ClientViewController::notify("create", "Error saving the client", false);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        if (!Gate::allows('update', $client)) {
            return ClientViewController::notify("index", "Inactive User", false);
        }
        return Inertia::render('EditClient', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        if (!Gate::allows('update', $client)) {
            return ClientViewController::notify("index", "Inactive User", false);
        }
        try {
            // Validar los datos
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:clients,email,' . $client->id,
                'company_name' => 'sometimes|string|max:255',
                'image_url' => 'sometimes|url|max:255',
            ]);
            // Actualizar el cliente

            $client->update($validated);


            return ClientViewController::notify("index", "Client updated succesfully");
        } catch (\Throwable $th) {
            return ClientViewController::notify("index", "Error updating the client", false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if (!Gate::allows('delete', $client)) {
            return ClientViewController::notify("index", "Inactive User", false);
        }
        try {
            ClientController::destroy($client);
            return ClientViewController::notify("index", "Client deleted");
        } catch (\Throwable $th) {
            return ClientViewController::notify("index", "Error deleting the client", false);
        }
    }

    public function notify(String $sub_route, String $message, bool $success = true): RedirectResponse
    {
        if (!$success) {
            return redirect()->route('clients.' . $sub_route)->with([
                'flash' => [
                    'banner' => $message,
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return redirect()->route('clients.' . $sub_route)->with([
            'flash' => [
                'banner' => $message,
                'bannerStyle' => 'success',
            ]
        ]);
    }
}
