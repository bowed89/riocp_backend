<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoSolicitud extends Model
{
    use HasFactory;
    protected $table = 'estados_solicitud';
    
    protected $fillable = [
        'tipo',
        'estado'
    ];
}
