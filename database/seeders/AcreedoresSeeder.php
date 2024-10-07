<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcreedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('acreedores')->insert([
            ['nombre' => 'FONDO NACIONAL DE DESARROLLO  REGIONAL', 'codigo' => 'FNDR'],
            ['nombre' => 'BANCO UNIÓN S.A.', 'codigo' => '']

        ]);
    }
}
