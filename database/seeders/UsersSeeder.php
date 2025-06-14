<?php

namespace Database\Seeders;

use App\Http\Controllers\SubscriptionController;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.es',
            'password' => Hash::make('password'),
            'admin' => 1
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@user.es',
            'password' => Hash::make('password')
        ]);
        SubscriptionController::create($admin);
        SubscriptionController::create($user);
    }
}
