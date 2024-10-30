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
        Schema::create('cronogramas_desembolso_programado', function (Blueprint $table) {
            $table->id();
            $table->string('objeto_deuda');
            $table->decimal('monto_contratado_a', 15, 2);
            $table->decimal('monto_desembolsado_b', 15, 2);
            $table->decimal('saldo_desembolso_a_b', 15, 2);
            $table->boolean('desembolso_desistido');
            $table->foreignId('acreedor_id')->constrained('acreedores')->onDelete('cascade');
            $table->foreignId('cronograma_main_id')->constrained('cronogramas_desembolso_programado_main')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronogramas_desembolso_programado');
    }
};
