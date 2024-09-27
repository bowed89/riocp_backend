<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estados_solicitud')->insert([
            [
                'tipo' => 'En proceso',
                'estado' => 1
            ],
            [
                'tipo' => 'Rechazado',
                'estado' => 1
            ],
            [
                'tipo' => 'Finalizado',
                'estado' => 1
            ],
            [
                'tipo' => 'Observado',
                'estado' => 1
            ]
        ]);
    }
}
