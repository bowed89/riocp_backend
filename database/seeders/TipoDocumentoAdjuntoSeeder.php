<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoAdjuntoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_documentos_adjunto')->insert([
            ['tipo' => 'Pagos', 'obligatorio' =>  true],
            ['tipo' => 'Desembolsos', 'obligatorio' =>  true],
            ['tipo' => 'Información Financiera', 'obligatorio' =>  false]
        ]);
    }
}
