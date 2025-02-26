<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\User;
use App\Services\CloudinaryService;
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
            return ClientViewController::notify("index", "Inactive User", false);
        }
        return Inertia::render('Clients/CreateClients');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!Gate::allows('create', Client::class)) {
            return ClientViewController::notify("index", "Inactive User", false);
        }
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
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            ]);;
            $user->clients()->attach($newClient->id);


            return ClientViewController::notify("index", "Client created succesfully");
        } catch (\Throwable $th) {
            dd($th);
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
            return ClientViewController::notify("index", "Inactive User", false);
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

            return ClientViewController::notify("index", "Client updated succesfully");
        } catch (\Throwable $th) {
            dd($th);
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
            if ($client->image_url) {
                CloudinaryService::deletePhoto($client->image_url, "client_logos");
            }
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
