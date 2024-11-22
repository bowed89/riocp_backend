<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcrEtaRubroExcel extends Model
{
    use HasFactory;
    protected $table = 'icr_eta_rubro_excel';
    protected $fillable = [
        'gestion',
        'entidad',
        'rubro',
        'monto',
    ];
}
