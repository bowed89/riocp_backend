<?php

namespace App\Http\Services\Operador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServicioDeudaService
{
    //SERVICIO DE LA DEUDA(LÍMITE 20%)
    public function obtenerServicioDeuda($codigo_entidad)
    {        
        $anioActual = Carbon::now()->year;

        $sumCapInteres = DB::table('fndr_excel')
            ->where('codigo_prsupuestario', $codigo_entidad)
            ->where('fecha_de_cuota', 'like', '%' . $anioActual . '%')
            ->selectRaw('SUM(capital::DECIMAL) + SUM(capital_diferido::DECIMAL) + 
                        SUM(interes::DECIMAL) + SUM(interes_diferido::DECIMAL) AS sum_cap_interes')
            ->first();

        // Subconsulta para el cálculo de promedio_icr_eta
        $promedioIcrEta = DB::table('icr_eta_rubro_total_excel')
            ->where('entidad', $codigo_entidad)
            ->where('nombre_total', 'ICR')
            ->selectRaw('ROUND(AVG(monto::DECIMAL), 2) AS promedio_icr_eta')
            ->first();

        if ($sumCapInteres && $promedioIcrEta && $promedioIcrEta->promedio_icr_eta != 0) {
            $resultadoFinal = round(($sumCapInteres->sum_cap_interes / $promedioIcrEta->promedio_icr_eta) * 100, 1);
        } else {
            $resultadoFinal = 0;
        }

        return $resultadoFinal;
    }
}
