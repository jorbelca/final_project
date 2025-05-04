<?php

namespace App\Http\Controllers;


use App\Models\Client;
use App\Models\User;
use App\Services\CloudinaryService;
use App\Services\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ClientViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 5); // Default to 5 if not provided
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener los clientes asociados al usuario y que no esten eliminados
        $clients = $user->clients()
            ->where('deleted', 0)
            ->orderBy('created_at', 'desc')
            ->paginate($size);

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
            return Notify::notify("clients.index", "Usuario inactivo", false);
        }
        return Inertia::render('Clients/CreateClients');
    }



    public function vinculate(Request $request)
    {
        try {
            $email = $request->email;
            $client = Client::where('email', $email)->first();
            if (!$client) {
                return Notify::notify("clients.create", "Cliente no encontrado", false);
            }
            $user = Auth::user();
            if (!$user) {
                return Notify::notify("clients.create", "Usuario Inactivo", false);
            }
            $user->clients()->attach($client->id);
            if ($client->deleted === '1') {
                $client->deleted = 0;
                $client->save();
            }
            return Notify::notify("clients.index", "Cliente vinculado correctamente");
        } catch (\Throwable $th) {
            Notify::notify("clients.create", "Error vinculando el cliente", false);
            throw new \Exception("Error vinculando el cliente", 0, $th);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!Gate::allows('create', Client::class)) {
            return Notify::notify("clients.index", "Usuario Inactivo", false);
        }
        try {
            // Obtener el usuario autenticado
            /** @var User $user */
            $user = Auth::user();
            if (!$user) {
                return redirect()->back()->with('error', 'Usuario no encontrado');
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


            return Notify::notify("clients.index", "Cliente creado correctamente");
        } catch (\Throwable $th) {
            Notify::notify("clients.create", "Error guardando el cliente", false);
            throw new \Exception("Error saving the client", 0, $th);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        if (!Gate::allows('update', $client)) {
            return Notify::notify("clients.index", "Usuario Inactivo", false);
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
            return Notify::notify("clients.index", "Usuario inactivo", false);
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

            return Notify::notify("clients.index", "Cliente actualizado correctamente");
        } catch (\Throwable $th) {

            Notify::notify("clients.index", "Error actualizando el cliente", false);
            throw new \Exception("Error updating the client", 0, $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $user = Auth::user();
        if (!Gate::allows('delete', $client)) {
            return Notify::notify("clients.index", "No estas autorizado", false);
        }
        try {
            // Si el usuario autenticado es el creador del cliente, reasignar el creador o eliminar el cliente
            if ($user->id == $client->created_by) {
                Client::reassignCreator($client);
            }
            // Desvincular el cliente del usuario
            $user->clients()->detach($client->id);
            return Notify::notify("clients.index", "Cliente eliminado correctamente");
        } catch (\Throwable $th) {
            Notify::notify("clients.index", "Error deleting the client", false);
            throw new \Exception("Error eliminando el cliente", 0, $th);
        }
    }
}
