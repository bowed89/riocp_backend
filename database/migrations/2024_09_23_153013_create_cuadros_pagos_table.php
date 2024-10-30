<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuadros_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('fecha_vencimiento');
            $table->decimal('capital', 15, 2); 
            $table->decimal('interes', 15, 2);
            $table->decimal('comisiones', 15, 2);
            $table->decimal('total', 15, 2);
            $table->decimal('saldo', 15, 2);
            $table->boolean('estado')->default(true);
            $table->foreignId('cronograma_servicio_id')->nullable()->constrained('cronogramas_servicio_deuda')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cronograma_deuda');
    }
};
