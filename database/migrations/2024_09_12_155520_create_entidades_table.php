<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /*     public function up(): void
    {
        Schema::create('entidades', function (Blueprint $table) {
            $table->id();
            $table->integer('entidad_id');
            $table->integer('par_tipo_institucion');
            $table->integer('par_departamento');
            $table->string('denominacion');
            $table->string('sigla')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
 */

    public function up(): void
    {
        Schema::create('entidades', function (Blueprint $table) {
            $table->bigInteger('id')->primary(); // Cambiado para que 'id' sea manual y no auto-incremental
            $table->integer('entidad_id');
            $table->integer('par_tipo_institucion');
            $table->integer('par_departamento');
            $table->string('denominacion');
            $table->string('sigla')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidades');
    }
};
