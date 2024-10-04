<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('periodos')->insert([
            ['tipo' => 'Mensual'],
            ['tipo' => 'Bimestral'],
            ['tipo' => 'Trimestral'],
            ['tipo' => 'Semestral'],
            ['tipo' => 'Anual'],
            ['tipo' => 'Cuatrimestral'],
            ['tipo' => 'Bianual']
        ]);
    }
}
