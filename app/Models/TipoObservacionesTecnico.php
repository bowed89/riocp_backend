<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoObservacionesTecnico extends Model
{
    use HasFactory;
    protected $table = 'tipos_observaciones_tecnico';

    protected $fillable = [
        'observacion',
        'estado'
    ];
}
