<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPestaniasSolicitante extends Model
{
    use HasFactory;
    protected $table = 'menus_pestanias_solicitante';
    protected $fillable = [
        'formulario_1',
        'formulario_2',
        'formulario_3',
        'formulario_4',
        'formulario_1_anexo',
        'sigep_anexo',
        'registro',
        'solicitud_id',
        'estado',
    ];
}
