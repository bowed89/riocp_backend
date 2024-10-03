<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_solicitud')->insert([
            ['tipo' => 'Solicitud de certificado RIOCP'],
            ['tipo' => 'Solicitud de Capacidad de Endeudamiento Referencial'],
        ]);
    }
}
