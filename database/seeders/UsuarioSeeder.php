<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{

    public function run(): void
    {

        DB::table('usuarios')->insert([
            [
                'nombre' => 'Juan',
                'apellido' => 'Olguin',
                'correo' => 'solicitante@gmail.com',
                'password' => Hash::make('juan12345'), 
                'estado' => true,
                'rol_id' => 1
            ],
            [
                'nombre' => 'Maria',
                'apellido' => 'Perez',
                'correo' => 'administrador@gmail.com',
                'password' => Hash::make('maria12345'), 
                'estado' => true,
                'rol_id' => 2
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Gutierrez',
                'correo' => 'operador@gmail.com',
                'password' => Hash::make('ana12345'),
                'estado' => true,
                'rol_id' => 3
            ]
        ]);
    }
}
