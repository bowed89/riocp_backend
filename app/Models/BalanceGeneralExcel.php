<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceGeneralExcel extends Model
{
    use HasFactory;
    protected $table = 'balance_general_excel';
    
    protected $fillable = [
        'gestion',
        'sistema_eeff',
        'nivel_institucional',
        'desc_estructura',
        'entidad',
        'desc_entidad',
        'cuenta',
        'desc_cuenta',
        'imputable',
        'saldo',
    ];
}
