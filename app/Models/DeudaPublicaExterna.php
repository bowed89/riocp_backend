<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaPublicaExterna extends Model
{
    use HasFactory;
    protected $table = 'deuda_publica_externa';

    protected $fillable = [
        'credito',
        'codigo',
        'entidad',
        'acreedor',
        'prestamo',
        'proyecto',
        'monto_autorizado_riocp',
        'monto_contratado',
        'monto_prestamo',
        'monto_desembolsado',
        'saldo_por_desembolsar',
        'plazo_anos',
        'gracia',
        'tasa_de_interes',
        'comision',
        'fecha_cuota',
        'capital_moneda_origen',
        'interes_moneda_origen',
        'comision_moneda_origen',
        'cuota_moneda_origen',
        'estado_prestamo',
        'moneda_origen',
        'tipo_cambio_sriocp',
        'tipo_cambio_valor',
        'fecha_del_tipo_de_cambio_del_tramite',
        'tipo_cambio_dinamico',
        'monto_autorizado_bs',
        'monto_contratado_bs',
        'monto_prestamo_bs',
        'monto_desembolsado_bs',
        'capital_bs',
        'interes_bs',
        'comision_bs',
        'codigo_riocp',
        'fecha_emision_certificado_riocp',
        'fecha_vigencia',
        'gestion',
        'meses',
        'si',
        'actualizacion_mensual_fndr',
    ];
}
