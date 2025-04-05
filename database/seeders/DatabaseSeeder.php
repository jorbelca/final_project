<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PlansSeeder::class);
        $this->call(UsersSeeder::class);


        // $this->call(SubscriptionsSeeder::class);
        // $this->call(ClientSeeder::class);

        // $this->call(BudgetsSeeder::class);
        // $this->call(CostsSeeder::class);

        // $this->call(Client_UserSeeder::class);
    }
}
