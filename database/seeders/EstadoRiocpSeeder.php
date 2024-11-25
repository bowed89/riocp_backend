<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoRiocpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estados_certificados_riocp')->insert([
            [
                'tipo' => 'Aceptado',
                'estado' => 1
            ],
            [
                'tipo' => 'Rechazado',
                'estado' => 1
            ]
        ]);
    }
}
