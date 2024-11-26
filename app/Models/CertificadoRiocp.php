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
        'servicio_deuda',
        'valor_presente_deuda_total',
        'solicitud_id',
        'estados_riocp_id',
    ];
}
