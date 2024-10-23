<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaServicioDeuda extends Model
{
    use HasFactory;
    protected $table = 'cronogramas_servicio_deuda';
    protected $fillable = [
        'objeto_deuda',
        'total_saldo',
        'total_capital',
        'total_interes',
        'total_comisiones',
        'total_sum',
        'solicitud_id',
        'acreedor_id',
        'moneda_id',
        'estado'
    ];
}
