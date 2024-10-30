<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cronogramas_servicio_deuda', function (Blueprint $table) {
            $table->id();
            $table->string('objeto_deuda');
            $table->decimal('total_saldo', 15, 2);
            $table->decimal('total_capital', 15, 2)->nullable();
            $table->decimal('total_interes', 15, 2)->nullable();
            $table->decimal('total_comisiones', 15, 2)->nullable();
            $table->decimal('total_sum', 15, 2)->nullable();
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('acreedor_id')->constrained('acreedores')->onDelete('cascade');
            $table->foreignId('moneda_id')->constrained('monedas')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cronogramas_servicio_deuda');
    }
};
