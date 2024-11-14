<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos')->insert([
            ['tipo' => 'inicio'],
            ['tipo' => 'notificaciones'],
            ['tipo' => 'tramites'],
            ['tipo' => 'certificados'],
            ['tipo' => 'reportes'],
            ['tipo' => 'ayuda'],
            ['tipo' => 'administracion'],
            ['tipo' => 'subir archivos'],
        ]);
    }
}
