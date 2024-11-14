<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balance_general_excel', function (Blueprint $table) {
            $table->id();
            $table->string('gestion')->nullable();
            $table->string('sistema_eeff')->nullable();
            $table->string('nivel_institucional')->nullable();
            $table->string('desc_estructura')->nullable();
            $table->string('entidad')->nullable();
            $table->string('desc_entidad')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('desc_cuenta')->nullable();
            $table->string('imputable')->nullable();
            $table->string('saldo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balance_general_excel');
    }
};
