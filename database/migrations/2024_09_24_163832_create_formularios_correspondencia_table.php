<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('formularios_correspondencia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('correo_electronico');
            $table->string('nombre_entidad');
            $table->string('cite_documento')->nullable();
            $table->string('referencia');
            $table->string('ruta_documento')->nullable(); // Columna para almacenar la ruta del documento
            $table->boolean('firma_digital')->default(false); // Indica si el documento fue cargado
            $table->boolean('estado')->default(true);
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade')->unique();
            $table->foreignId('tipo_solicitud_id')->nullable()->constrained('tipos_solicitud')->onDelete('cascade')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formularios_correspondencia');
    }
};
