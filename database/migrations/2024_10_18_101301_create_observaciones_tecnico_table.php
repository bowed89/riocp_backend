<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('observaciones_tecnico', function (Blueprint $table) {
            $table->id();
            $table->boolean('cumple');
            $table->foreignId('tipo_observacion_id')->constrained('tipos_observaciones_tecnico')->onDelete('cascade');
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observaciones_tecnico');
    }
};
