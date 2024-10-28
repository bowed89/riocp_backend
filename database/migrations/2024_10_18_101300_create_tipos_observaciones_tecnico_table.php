<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tipos_observaciones_tecnico', function (Blueprint $table) {
            $table->id();
            $table->string('enumeracion');
            $table->text('observacion');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_observaciones_tecnico');
    }
};
