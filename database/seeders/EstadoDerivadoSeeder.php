<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoDerivadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estados_derivado')->insert([
            ['tipo' => 'SIN DERIVAR'],
            ['tipo' => 'DERIVADO'],
        ]);
    }
}
