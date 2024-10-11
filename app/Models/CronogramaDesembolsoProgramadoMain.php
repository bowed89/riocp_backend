<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaDesembolsoProgramadoMain extends Model
{
    use HasFactory;
    protected $table = 'cronogramas_desembolso_programado_main';

    protected $fillable = [
        'solicitud_id',
        'estado'
    ];
}
