<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoNotasRiocpSeeder extends Seeder
{
    public function run(): void
    {


        DB::table('tipos_notas_riocp')->insert([
            [
                'tipo' => 'Nota Rechazo',
            ],
            [
                'tipo' => 'Nota Observación',
            ],
            [
                'tipo' => 'Nota Emisión',
            ],

        ]);
    }
}
