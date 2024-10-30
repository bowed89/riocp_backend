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
        Schema::create('fechas_desembolsos_programado', function (Blueprint $table) {
            $table->id();
            $table->string('fecha');
            $table->decimal('monto', 15, 2);
            $table->boolean('estado')->default(true);
            $table->foreignId('cronograma_id')->constrained('cronogramas_desembolso_programado')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desembolsos_programado');
    }
};
