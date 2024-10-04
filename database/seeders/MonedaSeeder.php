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
            ['tipo' => 'Dolar', 'sigla' => 'USD'],
            ['tipo' => 'Euro', 'sigla' => 'EUR'],
            ['tipo' => 'Bolivianos', 'sigla' => 'BOB'],
            ['tipo' => 'Yuan', 'sigla' => 'CNY'],
        ]);
    }
}
