<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcrEtaEpreExcel extends Model
{
    use HasFactory;
    protected $table = 'icr_eta_epre_excel';
    protected $fillable = [
        'entidad',
        'gestion',
        'epre',
        'monto',
    ];
}
