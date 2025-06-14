<?php

namespace App\Actions\Jetstream;

use App\Models\Client;
use App\Models\Support;
use App\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {   //Eliminar preguntas
        Support::where('questioner_id', $user->id)->delete();
        // Eliminar todos los budgets del usuario
        $user->budgets()->delete();
        // Obtener todos los clientes que este usuario creó
        $clients = Client::where('created_by', $user->id)->get();


        foreach ($clients as $client) {
            Client::reassignCreator($client);
        }
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
