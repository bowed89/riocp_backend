<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoAdjuntoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_documentos_adjunto')->insert([
            ['tipo' => 'Cronograma de Pagos', 'obligatorio' =>  true],
            ['tipo' => 'Cronograma de Desembolsos', 'obligatorio' =>  true],
            ['tipo' => 'Información Financiera', 'obligatorio' =>  false],
            ['tipo' => 'Deuda Pública Externa-Excel', 'obligatorio' =>  false],
            ['tipo' => 'FNDR-Excel', 'obligatorio' =>  false],
            ['tipo' => 'Balance General-Excel', 'obligatorio' =>  false],
            ['tipo' => 'Promedio ICR ETA-UP-Excel', 'obligatorio' =>  false],
        ]);
    }
}
