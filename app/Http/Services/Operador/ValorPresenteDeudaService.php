<?php

namespace App\Http\Services\Operador;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class  ValorPresenteDeudaService
{
    // 50% PASIVOS SIN CRONOGRAMAS
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

    // de la tabla 'deuda_publica_externa'
    // realizo uso del archivo bg_2023
    public function sumatoriaDeudaExterna()
    {
        $resultadoTotalDeudaExterna = DB::table('deuda_publica_externa')
            ->selectRaw("
            ROUND(
                SUM(
                    (CAST(capital_moneda_origen AS DECIMAL) + 
                    CAST(interes_moneda_origen AS DECIMAL)) / 
                    POWER(1 + 0.0299 / 365, 
                        (TO_DATE(fecha_cuota, 'DD/MM/YYYY') - TO_DATE('31/12/2023', 'DD/MM/YYYY'))
                    )
                ), 2
            ) AS resultado_total_deuda_externa")
            ->where('codigo', '1434')
            ->whereRaw("TO_DATE(fecha_cuota, 'DD/MM/YYYY') >= TO_DATE('2024-01-01', 'YYYY-MM-DD')")
            ->value('resultado_total_deuda_externa');
    }

    public function sumatoriaFndr()
    {
        $resultadoTotalFndr = DB::table('fndr_excel')
            ->selectRaw("
                ROUND(
                    SUM(
                        (
                        CAST(interes AS DECIMAL) + 
                        CAST(interes_diferido AS DECIMAL) + 
                        CAST(capital AS DECIMAL) + 
                        CAST(capital_diferido AS DECIMAL)
                        ) / 
                        POWER(1 + 0.0299 / 365, 
                            (TO_DATE(fecha_de_cuota, 'DD/MM/YYYY') - TO_DATE('31/12/2023', 'DD/MM/YYYY'))
                        )
                    ), 2
                ) AS resultado_total_fndr
            ")
            ->where('codigo_prsupuestario', '1434')
            ->whereRaw("TO_DATE(fecha_de_cuota, 'DD/MM/YYYY') >= TO_DATE('2024-01-01', 'YYYY-MM-DD')")
            ->value('resultado_total_fndr');
    }
}
