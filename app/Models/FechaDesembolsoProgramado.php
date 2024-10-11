<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechaDesembolsoProgramado extends Model
{
    use HasFactory;
    protected $table = 'fechas_desembolsos_programado';

    protected $fillable = [
        'fecha',
        'monto',
        'cronograma_id',
        'estado',
    ];
}
