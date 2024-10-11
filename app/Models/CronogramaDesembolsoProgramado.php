<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaDesembolsoProgramado extends Model
{
    use HasFactory;
    protected $table = 'cronogramas_desembolso_programado';

    protected $fillable = [
        'objeto_deuda',
        'monto_contratado_a',
        'monto_desembolsado_b',
        'saldo_desembolso_a_b',
        'desembolso_desistido',
        'acreedor_id',
        'cronograma_main_id',
        'estado',
    ];
}
