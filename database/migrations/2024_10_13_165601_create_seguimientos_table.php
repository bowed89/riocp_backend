<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_derivacion')->nullable();
            $table->string('observacion')->nullable();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('usuario_origen_id')->nullable()->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('usuario_destino_id')->nullable()->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('estado_derivado_id')->default(1)->constrained('estados_derivado')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
