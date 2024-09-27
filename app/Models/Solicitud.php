<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;
    protected $table = 'solicitudes';
    protected $fillable = [
        'cite',
        'nro_solicitud',
        'estado',
        'usuario_id',
        'estado_solicitud_id',
    ];

    public function Solicitud()
    {
        return $this->belongsTo(FormularioCorrespondencia::class);
    }
}
