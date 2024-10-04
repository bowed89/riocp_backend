<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IdentificadorCreditoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('identificadores_credito')->insert([
            ['nombre' => 'Crédito Público Interno', 'sigla' => 'CPI'],
            ['nombre' => 'rédito Público Externo', 'sigla' => 'CPE'],
        ]);
    }
}
