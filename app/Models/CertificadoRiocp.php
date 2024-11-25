<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificadoRiocp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'certificados_riocp';

    protected $fillable = [
        'nro_solicitud',
        'objeto_operacion_credito_pubico',
        'servicio_deuda',
        'valor_presente_deuda_total',
        'solicitud_id',
        'estados_riocp_id',
    ];
}
