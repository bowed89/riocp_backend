<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['rol' => 'Entidad Solicitante', 'descripcion' => 'Entidad solicitante', 'estado' => 1],
            ['rol' => 'Jefe de Unidad', 'descripcion' => 'Usuario que gestiona todo el sistema', 'estado' => 1],
            ['rol' => 'Técnico', 'descripcion' => 'Gestor para realizar operaciones de aprobacion de creditos', 'estado' => 1],
            ['rol' => 'Revisor', 'descripcion' => 'Encargado de verificar el estado de las solicitudes', 'estado' => 1],
            ['rol' => 'DGAFT', 'descripcion' => 'Director(a) General de Administración y Finanzas Territoriales', 'estado' => 1],
            ['rol' => 'VTCP', 'descripcion' => 'Viceministro del Tesoro y Crédito Público', 'estado' => 1],
            ['rol' => 'Administrador', 'descripcion' => 'Usuario que gestiona todo el sistema', 'estado' => 1],
        ]);
    }
}
