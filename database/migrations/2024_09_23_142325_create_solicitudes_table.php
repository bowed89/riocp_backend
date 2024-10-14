<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('nro_solicitud')->nullable();
            $table->string('nro_hoja_ruta')->nullable();
            $table->boolean('estado')->default(true);
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('estado_solicitud_id')->default(1)->constrained('estados_solicitud')->onDelete('cascade'); 
            $table->foreignId('estado_requisito_id')->default(1)->constrained('estados_requisito')->onDelete('cascade'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
