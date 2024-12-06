<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaCertificadoRiocp extends Model
{
    use HasFactory;
    protected $table = 'notas_certificado_riocp';
    protected $fillable = [
        'referencia',
        'header',
        'body',
        'footer',
        'certificado_riocp_id',
        'solicitud_id',
        'tipo_notas_id'
    ];
}
