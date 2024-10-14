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
            ['rol' => 'solicitante', 'descripcion' => 'Entidad solicitante', 'estado' => 1],
            ['rol' => 'administrador', 'descripcion' => 'Usuario que gestiona todo el sistema', 'estado' => 1],
            ['rol' => 'tecnico', 'descripcion' => 'Gestor para realizar operaciones de aprobacion de creditos', 'estado' => 1],
            ['rol' => 'revisor', 'descripcion' => 'Encargado de verificar el estado de las solicitudes', 'estado' => 1],
        ]);
    }
}
