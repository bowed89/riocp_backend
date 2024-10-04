<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informaciones_deuda', function (Blueprint $table) {
            $table->id();
            $table->boolean('pregunta_1')->nullable();
            $table->boolean('pregunta_2')->nullable();
            $table->boolean('pregunta_3')->nullable();
            $table->boolean('pregunta_4')->nullable();
            $table->boolean('estado')->default(true);
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informaciones_deuda');
    }
};
