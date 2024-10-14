<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimientos extends Model
{
    use HasFactory;
    protected $table = 'seguimientos';

    protected $fillable = [
        'observacion',
        'usuario_origen_id',
        'usuario_destino_id',
        'fecha_derivacion',
        'estado',
        'solicitud_id',
        'estado_derivado_id',
    ];
}
