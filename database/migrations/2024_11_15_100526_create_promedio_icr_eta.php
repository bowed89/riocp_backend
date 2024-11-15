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
            $table->string('gestion')->nullable();
            $table->string('c11000')->nullable();
            $table->string('c12000')->nullable();
            $table->string('c13000')->nullable();
            $table->string('c14000')->nullable();
            $table->string('c15000')->nullable();
            $table->string('c16000')->nullable();
            $table->string('c17000')->nullable();
            $table->string('c19211')->nullable();
            $table->string('c19212')->nullable();
            $table->string('c19216')->nullable();
            $table->string('c19219')->nullable();
            $table->string('c19220')->nullable();
            $table->string('c19230')->nullable();
            $table->string('c19260')->nullable();
            $table->string('c19270')->nullable();
            $table->string('c19280')->nullable();
            $table->string('c19300')->nullable();
            $table->string('c19400')->nullable();
            $table->string('total')->nullable();
            $table->string('c19212_org_119_idh')->nullable();
            $table->string('c19212_org_119_50_percent')->nullable();
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
