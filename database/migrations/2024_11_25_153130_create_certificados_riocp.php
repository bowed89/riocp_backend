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
        Schema::create('certificados_riocp', function (Blueprint $table) {
            $table->id();
            $table->integer('nro_solicitud');
            $table->string('objeto_operacion_credito_pubico');
            $table->string('servicio_deuda');
            $table->string('valor_presente_deuda_total');
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('estados_riocp_id')->nullable()->constrained('estados_certificados_riocp')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_certificados_riocp');
    }
};
