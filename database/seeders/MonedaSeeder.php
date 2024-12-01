<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('monedas')->insert([
            ['tipo' => 'Dolar', 'sigla' => 'USD', 'cambio' => 6.96],
            ['tipo' => 'Euro', 'sigla' => 'EUR', 'cambio' => 7.30],
            ['tipo' => 'Bolivianos', 'sigla' => 'BS', 'cambio' => 0.00],
            ['tipo' => 'Yuan', 'sigla' => 'CNY', 'cambio' => 0.95],
        ]);
    }
}
