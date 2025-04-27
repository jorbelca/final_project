<?php

namespace Database\Seeders;

use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('plans')->insert([
            [
                'name' => 'Plan Gratuito',
                'price' => 0.00,
                'duration_in_days' => 0,
                'features' => json_encode([
                    'Credits' => 0,
                    'Soporte prioritario' => false
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Plan Profesional',
                'price' => 9.99,
                'duration_in_days' => 30,
                'features' => json_encode([
                    'Funcionalidades avanzadas',
                    'Hasta 10 presupuestos mensuales con IA',
                    'Mejora de imagen profesional',
                    'Credits' => 40,
                    'Soporte prioritario' => false
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Plan Business',
                'price' => 19.99,
                'duration_in_days' => 30,
                'features' => json_encode([
                    'Soporte prioritario' => true,
                    'Credits' => 100
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
