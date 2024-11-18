<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('promedio_icr_eta', function (Blueprint $table) {
            $table->id();
            $table->string('entidad')->nullable();
            $table->string('gestion')->nullable();
            $table->string('11000')->nullable();
            $table->string('12000')->nullable();
            $table->string('13000')->nullable();
            $table->string('14000')->nullable();
            $table->string('15000')->nullable();
            $table->string('16000')->nullable();
            $table->string('17000')->nullable();
            $table->string('19211')->nullable();
            $table->string('19212')->nullable();
            $table->string('19216')->nullable();
            $table->string('19219')->nullable();
            $table->string('19220')->nullable();
            $table->string('19230')->nullable();
            $table->string('19260')->nullable();
            $table->string('19270')->nullable();
            $table->string('19280')->nullable();
            $table->string('19300')->nullable();
            $table->string('19400')->nullable();
            $table->string('total')->nullable();
            $table->string('19212_org_119_idh')->nullable();
            $table->string('19212_org_119_50_percent')->nullable();
            $table->string('icr')->nullable();
            $table->string('epre_19212')->nullable();
            $table->string('epre_41_119_19211')->nullable();
            $table->string('epre_41_119_19212')->nullable();
            $table->string('sumatoria_epre_19211_19212')->nullable();
            $table->string('epre_19216')->nullable();
            $table->string('epre_41_119_19216')->nullable();
            $table->string('epre_19219')->nullable();
            $table->string('epre_41_119_19219')->nullable();
            $table->string('epre_19220')->nullable();
            $table->string('epre_41_119_19220')->nullable();
            $table->string('epre_19230')->nullable();
            $table->string('epre_41_119_19230')->nullable();
            $table->string('epre_19260')->nullable();
            $table->string('epre_41_119_19260')->nullable();
            $table->string('epre_19270')->nullable();
            $table->string('epre_41_119_19270')->nullable();
            $table->string('epre_19280')->nullable();
            $table->string('epre_41_119_19280')->nullable();
            $table->string('epre_19300')->nullable();
            $table->string('epre_41_119_19300')->nullable();
            $table->string('epre_19400')->nullable();
            $table->string('epre_41_119_19400')->nullable();
            $table->string('total_41_119')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promedio_icr_eta');
    }
};
