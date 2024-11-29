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
            Schema::create('deuda_publica_externa', function (Blueprint $table) {
                $table->id();
                $table->string('credito')->nullable();
                $table->string('codigo')->nullable();
                $table->string('entidad')->nullable();
                $table->string('acreedor')->nullable();
                $table->string('prestamo')->nullable();
                $table->string('proyecto', 700)->nullable(); // 700 caracteres 
                $table->string('monto_autorizado_riocp')->nullable();
                $table->string('monto_contratado')->nullable();
                $table->string('monto_prestamo')->nullable();
                $table->string('monto_desembolsado')->nullable();
                $table->string('saldo_por_desembolsar')->nullable();
                $table->string('plazo_anos')->nullable();
                $table->string('gracia')->nullable();
                $table->string('tasa_de_interes')->nullable();
                $table->string('comision')->nullable();
                $table->string('fecha_cuota')->nullable();
                $table->string('capital_moneda_origen')->nullable();
                $table->string('interes_moneda_origen')->nullable();
                $table->string('comision_moneda_origen')->nullable();
                $table->string('cuota_moneda_origen')->nullable();
                $table->string('estado_prestamo')->nullable();
                $table->string('moneda_origen')->nullable();
                $table->string('tipo_cambio_sriocp')->nullable();
                $table->string('tipo_cambio_valor')->nullable();
                $table->string('fecha_del_tipo_de_cambio_del_tramite')->nullable();
                $table->string('tipo_cambio_dinamico')->nullable();
                $table->string('monto_autorizado_bs')->nullable();
                $table->string('monto_contratado_bs')->nullable();
                $table->string('monto_prestamo_bs')->nullable();
                $table->string('monto_desembolsado_bs')->nullable();
                $table->string('capital_bs')->nullable();
                $table->string('interes_bs')->nullable();
                $table->string('comision_bs')->nullable();
                $table->string('codigo_riocp')->nullable();
                $table->string('fecha_emision_certificado_riocp')->nullable();
                $table->string('fecha_vigencia')->nullable();
                $table->string('gestion')->nullable();
                $table->string('meses')->nullable();
                $table->string('si')->nullable();
                $table->string('actualizacion_mensual_fndr')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('deuda_publica_externa');
        }
    };
