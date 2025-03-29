<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.es',
            'password' => Hash::make('password'),
            'admin' => 1
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@user.es',
            'password' => Hash::make('password')
        ]);
    }
}
