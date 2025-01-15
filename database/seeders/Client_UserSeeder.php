<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;

class Client_UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios y clientes creados
        $users = User::all();
        $clients = Client::all();

        // Asociar clientes a usuarios aleatoriamente
        foreach ($clients as $client) {
            // Cada cliente se asociará con 1-3 clientes aleatorios

            $client->users()->attach(
                $users->random(rand(1, 1))->pluck('id')->toArray()
            );
        }
    }
}
