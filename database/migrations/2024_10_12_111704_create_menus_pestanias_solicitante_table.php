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
        Schema::create('menus_pestanias_solicitante', function (Blueprint $table) {
            $table->id();
            $table->boolean('formulario_1')->default(false);
            $table->boolean('formulario_2')->default(true);
            $table->boolean('formulario_3')->default(true);
            $table->boolean('formulario_4')->default(true);
            $table->boolean('formulario_1_anexo')->default(true);
            $table->boolean('sigep_anexo')->default(true);
            $table->boolean('registro')->default(true);
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus_pestanias_solicitante');
    }
};
