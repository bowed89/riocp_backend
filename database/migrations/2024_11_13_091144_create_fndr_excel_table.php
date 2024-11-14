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
        Schema::create('fndr_excel', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_prsupuestario')->nullable();
            $table->string('entidad')->nullable();
            $table->string('prestamo')->nullable();
            $table->string('programa')->nullable();
            $table->string('proyecto')->nullable();
            $table->string('monto_contratado')->nullable();
            $table->string('monto_prestamo')->nullable();
            $table->string('fecha_desembolso')->nullable();
            $table->string('monto_desembolsado')->nullable();
            $table->string('plazo')->nullable();
            $table->string('gracia')->nullable();
            $table->string('fecha_de_vigencia')->nullable();
            $table->string('cuota')->nullable();
            $table->string('fecha_de_cuota')->nullable();
            $table->string('tasa_fecha_cuota')->nullable();
            $table->string('capital')->nullable();
            $table->string('interes')->nullable();
            $table->string('capital_diferido')->nullable();
            $table->string('interes_diferido')->nullable();
            $table->string('cuentas_por_cobrar')->nullable();
            $table->string('total_de_la_cuota')->nullable();
            $table->string('estado_de_la_cuota')->nullable();
            $table->string('estado_del_prestamo')->nullable();
            $table->string('moneda_del_prestamo')->nullable();
            $table->string('fecha_de_pago')->nullable();
            $table->string('saldo_de_capital_de_la_deuda')->nullable();
            $table->string('capital_amortizado')->nullable();
            $table->string('interes_cobrado')->nullable();
            $table->string('comisiones_cobradas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fndr');
    }
};
