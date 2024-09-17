<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { {
            DB::table('entidades')->insert([
                ['denominacion' => 'Gobierno Autónomo Municipal de Yotala', 'sigla' => null],
                ['denominacion' => 'Gobierno Autónomo Municipal de Poroma', 'sigla' => null],
                ['denominacion' => 'Gobierno Autónomo Municipal de Villa Azurduy', 'sigla' => null],
                ['denominacion' => 'Gobierno Autónomo Municipal de Tarvita (Villa Orías)', 'sigla' => null],
                ['denominacion' => 'Gobierno Autónomo Municipal de Villa Zudañez (Tacopaya)', 'sigla' => null],
                ['denominacion' => 'Gobierno Autónomo Municipal de Presto', 'sigla' => null],
                ['denominacion' => 'Gobierno Autónomo Municipal de Villa Mojocoya', 'sigla' => null],
                ['denominacion' => 'Gobierno Autónomo Municipal de Icla', 'sigla' => null],
            ]);
        }
    }
}
