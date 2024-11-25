<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\SolicitudTemporal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolSeeder::class);
        $this->call(TipoSeeder::class);
        $this->call(AcreedoresSeeder::class);
        $this->call(EstadoDerivadoSeeder::class);
        $this->call(IdentificadorCreditoSeeder::class);
        $this->call(MonedaSeeder::class);
        $this->call(TipoObservacionSeeder::class);
        $this->call(TipoDocumentoAdjuntoSeeder::class);
        $this->call(PeriodoSeeder::class);
        $this->call(TipoSolicitudSeeder::class);
        $this->call(EstadoRequisitoSeeder::class);
        $this->call(EstadoSolicitudSeeder::class);
        $this->call(EntidadSeeder::class);
        $this->call(UsuarioSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(EstadoRiocpSeeder::class);
    }
}
