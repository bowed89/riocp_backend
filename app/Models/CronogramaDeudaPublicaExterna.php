<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaDeudaPublicaExterna extends Model
{
    use HasFactory;
    protected $table = 'cronogramas_deuda_publica_externa';

    // Campos asignables
    protected $fillable = [
        'no_prestamos',
        'no_tramos',
        'prov_fondos',
        'moneda_del_tramo',
        'nombre_del_acreedor',
        'concepto',
        'moneda',
        'fecha_de_vencimiento',
        'saldo_adeudado_al_31_12_2022',
    ];

    // Campos bianuales desde 2023 hasta 2059
    public function __construct(array $attributes = [])
    {
        for ($year = 2023; $year <= 2059; $year++) {
            $this->fillable[] = "{$year}/1";
            $this->fillable[] = "{$year}/2";
        }
        parent::__construct($attributes);
    }
}
