<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeudaPublicaExterna extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'deuda_publica_externa';
    
    protected $fillable = [
        'prov_fondos',
        'no_prestamos',
        'no_tramos',
        'nombre_del_acreedor',
        'referencia_del_acreedor',
        'fecha_de_firma',
        'moneda_del_tramo',
        'monto_del_tramo',
        'monto_del_prestamo',
        'plazo',
        'tasa_de_interes',
        'objeto',
        'nombre',
        'situacion',
        'periodo_de_gracia',
    ];
}
