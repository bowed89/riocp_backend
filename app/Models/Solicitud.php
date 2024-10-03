<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;
    protected $table = 'solicitudes';
    protected $fillable = [
        'nro_solicitud',
        'estado',
        'usuario_id',
        'estado_solicitud_id',
    ];

    public function Formulario()
    {
        return $this->belongsTo(FormularioCorrespondencia::class);
    }
}
