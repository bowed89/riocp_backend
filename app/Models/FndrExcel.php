<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FndrExcel extends Model
{
    use HasFactory;
    protected $table = 'fndr_excel';

    protected $fillable = [
        'codigo_prsupuestario',
        'entidad',
        'prestamo',
        'programa',
        'proyecto',
        'monto_contratado',
        'monto_prestamo',
        'fecha_desembolso',
        'monto_desembolsado',
        'plazo',
        'gracia',
        'fecha_de_vigencia',
        'cuota',
        'fecha_de_cuota',
        'tasa_fecha_cuota',
        'capital',
        'interes',
        'capital_diferido',
        'interes_diferido',
        'cuentas_por_cobrar',
        'total_de_la_cuota',
        'estado_de_la_cuota',
        'estado_del_prestamo',
        'moneda_del_prestamo',
        'fecha_de_pago',
        'saldo_de_capital_de_la_deuda',
        'capital_amortizado',
        'interes_cobrado',
        'comisiones_cobradas',
    ];
}
