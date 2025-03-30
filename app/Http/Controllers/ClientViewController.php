<?php

namespace App\Http\Controllers;


use App\Models\Client;
use App\Models\User;
use App\Services\CloudinaryService;
use App\Services\Notify;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        // Obtener los clientes asociados al usuario y que no esten eliminados
        $clients = $user->clients->where('deleted', 0);

        return Inertia::render('Clients/Clients', [
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('view', new Client())) {
            return Notify::notify("clients.index", "Inactive User", false);
        }
        return Inertia::render('Clients/CreateClients');
    }



    public function vinculate(Request $request)
    {
        try {
            $email = $request->email;
            $client = Client::where('email', $email)->first();
            if (!$client) {
                return Notify::notify("clients.create", "Client not found", false);
            }
            $user = Auth::user();
            if (!$user) {
                return Notify::notify("clients.create", "Inactive User", false);
            }
            $user->clients()->attach($client->id);
            if ($client->deleted === '1') {
                $client->deleted = 0;
                $client->save();
            }
            return Notify::notify("clients.index", "Client vinculated succesfully");
        } catch (\Throwable $th) {
            return Notify::notify("clients.create", "Error vinculating the client", false);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!Gate::allows('create', Client::class)) {
            return Notify::notify("clients.index", "Inactive User", false);
        }
        try {
            // Obtener el usuario autenticado
            /** @var User $user */
            $user = Auth::user();
            if (!$user) {
                return redirect()->back()->with('error', 'No authenticated user found.');
            }
            $request->merge(['user_id' => $user->id]);
            $request->merge(['created_by' => $user->id]);

            // Validar los datos
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email',
                'company_name' => 'required|string|max:255',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'created_by' => 'required|exists:users,id',
            ]);


            // Subir la imagen a Cloudinary si está presente en el array $validated
            if (array_key_exists('image_url', $validated) && $validated['image_url'] instanceof \Illuminate\Http\UploadedFile) {
                $validated['image_url'] = CloudinaryService::uploadPhoto($validated['image_url'], "client_logos");
            } else {
                $validated['image_url'] = null;
            }

            // Crear el cliente
            $newClient = Client::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'company_name' => $validated['company_name'],
                'image_url' => $validated['image_url'] ?? null,
                'created_by' => $validated['created_by'],
            ]);;
            $user->clients()->attach($newClient->id);


            return Notify::notify("clients.index", "Client created succesfully");
        } catch (\Throwable $th) {
            dd($th);
            return Notify::notify("clients.create", "Error saving the client", false);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        if (!Gate::allows('update', $client)) {
            return Notify::notify("clients.index", "Inactive User", false);
        }
        return Inertia::render('Clients/EditClient', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $client = Client::findOrFail($request->id);

        if (!Gate::allows('update', $client)) {
            return Notify::notify("clients.index", "Inactive User", false);
        }
        try {
            $imageValidationRule = 'nullable|url';  // Validamos como URL si ya tiene una URL en la request

            if ($request->hasFile('image_url')) {
                $imageValidationRule = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';  // Validamos como imagen si se ha subido una nueva
            }
            // Validar los datos
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'nullable|email|unique:clients,email,' . $client->id,
                'company_name' => 'sometimes|string|max:255',
                'image_url' => $imageValidationRule,
            ]);

            // Subir la imagen a Cloudinary si está presente en el array $validated
            if (array_key_exists('image_url', $validated) && $validated['image_url'] instanceof \Illuminate\Http\UploadedFile) {
                if ($client->image_url) {
                    CloudinaryService::deletePhoto($client->image_url, "client_logos");
                }
                $validated['image_url'] = CloudinaryService::uploadPhoto($validated['image_url'], "client_logos");
            }


            // Actualizar el cliente
            $client->update($validated);

            return Notify::notify("clients.index", "Client updated succesfully");
        } catch (\Throwable $th) {
            dd($th);
            return Notify::notify("clients.index", "Error updating the client", false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $user = Auth::user();
        if (!Gate::allows('delete', $client)) {
            return Notify::notify("clients.index", "You are not allowed to do that", false);
        }
        try {
            // Si el usuario autenticado es el creador del cliente, reasignar el creador o eliminar el cliente
            if ($user->id == $client->created_by) {
                Client::reassignCreator($client);
            }
            // Desvincular el cliente del usuario
            $user->clients()->detach($client->id);
            return Notify::notify("clients.index", "Client deleted");
        } catch (\Throwable $th) {
            return Notify::notify("clients.index", "Error deleting the client", false);
        }
    }
}
