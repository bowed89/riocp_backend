<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialDocumentoExcel extends Model
{
    use HasFactory;
    protected $table = 'historiales_documentos_excel';

    protected $fillable = [
        'ruta_documento',
        'tipo_documento_id',
        'usuario_id',
        'estado'
    ];
}
