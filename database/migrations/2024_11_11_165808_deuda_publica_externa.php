<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deuda_publica_externa', function (Blueprint $table) {
            $table->id();
            $table->string('prov_fondos');
            $table->string('no_prestamos');
            $table->string('no_tramos');
            $table->string('nombre_del_acreedor');
            $table->string('referencia_del_acreedor');
            $table->string('fecha_de_firma');
            $table->string('moneda_del_tramo');
            $table->string('monto_del_tramo');
            $table->string('monto_del_prestamo');
            $table->string('plazo');
            $table->string('tasa_de_interes');
            $table->string('objeto');
            $table->string('nombre');
            $table->string('situacion');
            $table->string('periodo_de_gracia');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    public function down(): void
    {
        //
    }
};
