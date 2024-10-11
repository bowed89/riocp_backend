<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoAdjunto extends Model
{
    use HasFactory;
    protected $table = 'documentos_adjunto';

    protected $fillable = [
        'ruta_documento',
        'solicitud_id',
        'tipo_documento_id',
        'estado'
    ];
}
