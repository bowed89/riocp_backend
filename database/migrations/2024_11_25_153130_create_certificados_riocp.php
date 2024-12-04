<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('certificados_riocp', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nro_solicitud')->nullable();
            $table->decimal('servicio_deuda', 15, 2);
            $table->decimal('valor_presente_deuda_total', 15, 2);
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('estados_riocp_id')->nullable()->constrained('estados_certificados_riocp')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados_riocp');
    }
};
