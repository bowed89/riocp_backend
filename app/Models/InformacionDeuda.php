<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionDeuda extends Model
{
    use HasFactory;
    protected $table = 'informaciones_deuda';
    protected $fillable = [
        'pregunta_1',
        'pregunta_2',
        'pregunta_3',
        'pregunta_4',
        'estado',
        'solicitud_id',
    ];
}
