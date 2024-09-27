<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cronogramas_servicio_deuda', function (Blueprint $table) {
            $table->id();
            $table->string('objeto_deuda');
            $table->string('total_capital');
            $table->string('total_interes');
            $table->string('total_comisiones');
            $table->string('total');
            $table->foreignId('cuadro_pago_id')->nullable()->constrained('cuadros_pagos')->onDelete('cascade');
            $table->foreignId('acreedor_id')->constrained('acreedores')->onDelete('cascade');
            $table->foreignId('moneda_id')->constrained('monedas')->onDelete('cascade');
            $table->boolean('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cronogramas_servicio_deuda');
    }
};
