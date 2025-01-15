<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Client::all();
        } catch (\Throwable $th) {

            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        try {

            $validated = $request->validated();

            // Crear el cliente
            $newClient = Client::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'company_name' => $validated['company_name'],
                'image_url' => $validated['image_url'] ?? null,
            ]);

            // Obtener el usuario relacionado
            $user = User::findOrFail($validated['user_id']);

            // Relacionar cliente con usuario en la tabla intermedia
            $user->clients()->attach($newClient->id);

            return response()->json(['New Client' => $newClient], 201);
        } catch (\Throwable $th) {

            return response()->json(['message' => 'Error saving the client'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        try {
            return $client;
        } catch (\Throwable $th) {

            return response()->json(['message' => 'Error'], 400);
        }
    }

    // Show the budgets of one client
    public function showClientBudgets(string $id)
    {
        try {
            $client = Client::with('budgets')->findOrFail($id);

            return response()->json(['Budgets sent to ' . $client->name => $client->budgets]);
        } catch (\Throwable $th) {

            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        try {

            $validated = $request->validated();

            $client->update($validated);

            return response()->json(['message' => 'Client Updated ', "clientUpdated" => $client], 200);
        } catch (\Throwable $th) {

            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {

        try {
            $client->users()->detach();
            $client->delete();

            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {
            // Para cualquier otro error
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
