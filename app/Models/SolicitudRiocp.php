<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudRiocp extends Model
{
    use HasFactory;
    protected $table = 'solicitudes_riocp';

    protected $fillable = [
        'monto_total',
        'plazo',
        'interes_anual',
        'comision_concepto',
        'comision_tasa',
        'declaracion_jurada',
        'periodo_gracia',
        'objeto_operacion_credito',
        'firma_digital',
        'ruta_documento',
        'solicitud_id',
        'acreedor_id',
        'moneda_id',
        'entidad_id',
        'identificador_id',
        'periodo_id',
        'contacto_id',
        'estado',
    ];
}
