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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->string('nombre_usuario')->unique();
            $table->integer('ci')->unique();
            $table->string('password', 255);
            $table->boolean('estado');
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('entidad_id')->nullable()->constrained('entidades')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
