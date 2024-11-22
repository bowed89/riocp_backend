<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcrEtaRubroTotalExcel extends Model
{
    use HasFactory;
    protected $table = 'icr_eta_rubro_total_excel';
    protected $fillable = [
        'entidad',
        'gestion',
        'nombre_total',
        'monto',
    ];
}
