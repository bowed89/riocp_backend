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
        Schema::create('solicitudes_riocp', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto_total', 10, 2);
            $table->integer('plazo');
            $table->decimal('interes_anual', 10, 2);
            $table->string('comisiones')->nullable();
            $table->integer('periodo_gracia');
            $table->string('objeto_operacion_credito');
            $table->boolean('firma_digital')->default(false); // si el formulario valida la firma..
            $table->string('ruta_documento')->nullable(); // Columna para almacenar la ruta del documento
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('acreedor_id')->constrained('acreedores')->onDelete('cascade');
            $table->foreignId('moneda_id')->constrained('monedas')->onDelete('cascade');
            $table->foreignId('entidad_id')->constrained('entidades')->onDelete('cascade');
            $table->foreignId('identificador_id')->constrained('identificadores_credito')->onDelete('cascade');
            $table->foreignId('periodo_id')->constrained('periodos')->onDelete('cascade');
            $table->foreignId('contacto_id')->constrained('contactos_subsanar')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_riocp');
    }
};
