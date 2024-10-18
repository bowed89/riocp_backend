<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservacionTecnico extends Model
{
    use HasFactory;
    protected $table = 'observaciones_tecnico';

    protected $fillable = [
        'cumple',
        'tipo_observacion_id',
        'solicitud_id',
        'usuario_id',
    ];
}
