<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('icr_eta_rubro_excel', function (Blueprint $table) {
            $table->id();
            $table->string('gestion')->nullable();
            $table->string('entidad')->nullable();
            $table->string('rubro')->nullable();
            $table->string('monto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('icr_eta_rubro_excel');
    }
};