<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\StoreCostRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return Inertia::render('CreateClients');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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


            return redirect()->route('clients.index')->with('success', 'Client created successfully!');
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error saving the client: ' . $th->getMessage()], 400);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return Inertia::render('EditClient', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
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


            return redirect()->route('clients.index')->with('success', 'Client updated successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Error updating the client: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try {
            ClientController::destroy($client);
            return redirect()->route('costs.index')->with('success', 'Deleted');
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
