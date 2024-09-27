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
            $table->string('capital');
            $table->string('interes');
            $table->string('comisiones');
            $table->string('total');
            $table->string('saldo');
            $table->boolean('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cronograma_deuda');
    }
};
