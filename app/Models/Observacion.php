<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    use HasFactory;
    protected $table = 'observaciones';

    protected $fillable = [
        'cumple',
        'observacion',
        'tipo_observacion_id',
        'solicitud_id',
        'usuario_id',
        'rol_id',
    ];
}
