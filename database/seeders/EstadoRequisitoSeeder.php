<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoRequisitoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estados_requisito')->insert([
            [
                'tipo' => 'Incompleto',
                'estado' => 1
            ],
            [
                'tipo' => 'Completado',
                'estado' => 1
            ]
        ]);
    }
}
