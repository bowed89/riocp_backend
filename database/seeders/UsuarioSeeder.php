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
                'nombre' => 'Juan Carlos',
                'apellido' => 'Perez',
                'correo' => 'jefe.unidad@gmail.com',
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
                'nombre' => 'Carlos Alberto',
                'apellido' => 'Robles',
                'correo' => 'operador2@gmail.com',
                'nombre_usuario' => 'carlos.operador',
                'ci' => 98002323,
                'password' => Hash::make('operador123'),
                'estado' => true,
                'rol_id' => 3,
                'entidad_id' => null,

            ],
            [
                'nombre' => 'Fabio',
                'apellido' => 'Zenteno',
                'correo' => 'revisor@gmail.com',
                'nombre_usuario' => 'fabio.revisor',
                'ci' => 5675665,
                'password' => Hash::make('revisor123'),
                'estado' => true,
                'rol_id' => 4,
                'entidad_id' => null,

            ]
        ]);
    }
}
