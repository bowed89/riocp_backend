<?php

namespace Database\Seeders;
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
                'nombre_usuario' => 'juan.solicitante',
                'ci' => 98008716,
                'password' => Hash::make('solicitante123'), 
                'estado' => true,
                'rol_id' => 1,
                'entidad_id' => 4,
            ],
            [
                'nombre' => 'Maria',
                'apellido' => 'Perez',
                'correo' => 'administrador@gmail.com',
                'nombre_usuario' => 'maria.admin',
                'ci' => 34555233,
                'password' => Hash::make('administrador123'), 
                'estado' => true,
                'rol_id' => 2,
                'entidad_id' => null,

            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Gutierrez',
                'correo' => 'operador@gmail.com',
                'nombre_usuario' => 'ana.operador',
                'ci' => 9800876,
                'password' => Hash::make('operador123'),
                'estado' => true,
                'rol_id' => 3,
                'entidad_id' => null,

            ],
            [
                'nombre' => 'Fabio',
                'apellido' => 'Zenteno',
                'correo' => 'seguimiento@gmail.com',
                'nombre_usuario' => 'fabio.seguimiento',
                'ci' => 5675665,
                'password' => Hash::make('seguimiento123'),
                'estado' => true,
                'rol_id' => 4,
                'entidad_id' => null,

            ],
            [
                'nombre' => 'Wendy',
                'apellido' => 'Ticona',
                'correo' => 'administrador2@gmail.com',
                'nombre_usuario' => 'wendy.administrador',
                'ci' => 678745,
                'password' => Hash::make('administrador123'),
                'estado' => true,
                'rol_id' => 2,
                'entidad_id' => null,

            ]
        ]);
    }
}
