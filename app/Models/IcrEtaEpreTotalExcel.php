<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcrEtaEpreTotalExcel extends Model
{
    use HasFactory;
    protected $table = 'icr_eta_epre_total_excel';
    protected $fillable = [
        'entidad',
        'gestion',
        'nombre_total',
        'monto',
    ];
}
