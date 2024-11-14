<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cronogramas_deuda_publica_externa', function (Blueprint $table) {
            $table->id();
            $table->string('no_prestamos')->nullable();
            $table->string('no_tramos')->nullable();
            $table->string('prov_fondos')->nullable();
            $table->string('moneda_del_tramo')->nullable();
            $table->string('nombre_del_acreedor')->nullable();
            $table->string('concepto')->nullable();
            $table->string('moneda')->nullable();
            $table->string('fecha_de_vencimiento')->nullable();
            $table->string('saldo_adeudado_al_31_12_2022')->nullable();

            // Campos para cada periodo desde 2023 hasta 2059
            for ($year = 2023; $year <= 2059; $year++) {
                $table->string("{$year}/1")->nullable();
                $table->string("{$year}/2")->nullable();
            }

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronogramas_deuda_publica_externa');
    }
};
