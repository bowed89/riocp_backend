<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuadroPago extends Model
{
    use HasFactory;
    protected $table = 'cuadros_pagos';
    
    protected $fillable = [
        'fecha_vencimiento',
        'capital',
        'interes',
        'comisiones',
        'total',
        'saldo',
        'estado',
        'cronograma_servicio_id'
    ];
}
