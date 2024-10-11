<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumentoAdjunto extends Model
{
    use HasFactory;
    protected $table = 'tipos_documentos_adjunto';
    
    protected $fillable = [
        'tipo',
        'obligatorio',
        'estado',
    ];

}
