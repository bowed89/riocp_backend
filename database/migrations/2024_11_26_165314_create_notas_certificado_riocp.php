<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('notas_certificado_riocp', function (Blueprint $table) {
            $table->id();
            $table->text('referencia');
            $table->text('header');
            $table->text('body');
            $table->text('footer');
            $table->foreignId('certificado_riocp_id')->nullable()->constrained('certificados_riocp')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_certificado_riocp');
    }
};
