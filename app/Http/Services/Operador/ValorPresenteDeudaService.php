<?php

namespace App\Http\Services\Operador;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class  ValorPresenteDeudaService
{
    /**************************************************************
                        VALOR PRESENTE DE LA DEUDA
     ***************************************************************/

    /* 
            Según la formula :
            
            ************************************************
            VPD =   VP + 50% Pasivos sin cronogramas
                    ---------------------------------
                         Promedio 4 años
            ************************************************
                            ::::VP::::
        
            Para hallar el VP(valor presente) se debe usar la formula
                         VF
            VP =      -----------
                       (1+i)^((01/05/2024) - (31/12/2023))

            el VP se obtiene filtrando los valores entre las fechas 
            del año en curso (se debe filtrar de la columna 'fecha_cuota')
            para adelante de los archivos:

              - CREDITOS EXTERNOS Y OTROS:
                VF: es la sumatoria de las columnas  
                    capital_moneda_origen + interes_moneda_origen
                    i: tasa de descuento, por ej es 2.99 lo convierto a:  0.299
                                                                      ------
                                                                        365
              - INFORMACION DEUDA ENTIDADES  TERRITORIALES AUTONOMAS.
                VF: es la sumatoria de las columnas  
                interes + interes_diferido + capital + capital_diferido
                i: tasa de descuento, por ej es 2.99 lo convierto a:  0.299
                                                                      ------
                                                                        365
                con esa formula debo realizar esa operacion con todas las filas 
                filtradas y sumarlos sus resultados...

                                :::::50% de la  pasivos sin cronogramas:::::

                Se obtiene del archivo  BG 2023, se filtra por la columna 'entidad',
                luego filtro los pasivos con los codigos:
                        '2111', '2112', '2113', '2114', '2115',
                        '2116', '2118', '2119', '2121', '2122',
                        '213', '2141', '2142', '2143', '2151',
                        '2152', '2153', '2154', '216', '217', '2211', '2212',
                        '2221', '2222', '224', '2251', '2252', '2253', '226'

                y lo multiplico X 0.5 su columna 'saldo'. 

                
                luego hago la sumatoria de:
                VP(CREDITOS EXTERNOS Y OTROS) + 
                VP (INFORMACION DEUDA ENTIDADES  TERRITORIALES AUTONOMAS) + 
                50% de la deuda / Promedio
    */

    /*************************************************************
                            HALLAMOS  VP DEL ARCHIVO 
                            CREDITOS EXTERNOS Y OTROS .XLS
     ************************************************************/

    /*    public function sumatoriaDeudaCreditoExterno($tasaDescuento, $codigo_entidad)
    {
        // Obtener fechas necesarias
        $diciembre31 = Carbon::now()->subYear()->endOfYear()->format('Y-m-d'); // 2023-12-31
        $enero1 = Carbon::now()->startOfYear()->format('Y-m-d'); // 2024-01-01

        // Construir la consulta
        $resultadoTotalDeudaExterna = DB::table('deuda_publica_externa')
            ->selectRaw("
            ROUND(
                SUM(
                    (CAST(capital_moneda_origen AS NUMERIC) + 
                    CAST(interes_moneda_origen AS NUMERIC)) / 
                    POWER(1 + ? / 365, 
                        (fecha_cuota::DATE - ?::DATE)
                    )
                ), 2
            ) AS resultado_total_deuda_externa", [$tasaDescuento, $diciembre31])
            ->where('codigo', $codigo_entidad)
            ->where('fecha_cuota', '>=', $enero1)
            ->value('resultado_total_deuda_externa');

        return $resultadoTotalDeudaExterna;
    } */

    public function sumatoriaDeudaCreditoExterno($tasaDescuento, $codigo_entidad)
    {
        // Obtener 31 de diciembre del año pasado
        $diciembre31 = Carbon::now()->subYear()->endOfYear();
        $diciembre31Formateado = $diciembre31->format('d/m/Y'); // Ejemplo: 31/12/2023

        // Obtener 1 de enero del año en curso
        $enero1 = Carbon::now()->startOfYear();
        $enero1Formateado = $enero1->format('Y-m-d'); // Ejemplo: 2024-01-01

        // Construir el query dinámicamente
        $resultadoTotalDeudaExterna = DB::table('deuda_publica_externa')
            ->selectRaw("
                 ROUND(
                     SUM(
                         (CAST(capital_moneda_origen AS DECIMAL) + 
                         CAST(interes_moneda_origen AS DECIMAL)) / 
                         POWER(1 + $tasaDescuento / 365, 
                             (TO_DATE(fecha_cuota, 'DD/MM/YYYY') - TO_DATE('$diciembre31Formateado', 'DD/MM/YYYY'))
                         )
                     ), 2
                 ) AS resultado_total_deuda_externa
             ")
            ->where('codigo', $codigo_entidad)
            ->whereRaw("TO_DATE(fecha_cuota, 'DD/MM/YYYY') >= TO_DATE('$enero1Formateado', 'YYYY-MM-DD')")
            ->value('resultado_total_deuda_externa');

        return $resultadoTotalDeudaExterna;
    }
    /***************************************************************
        HALLAMOS  VP DEL ARCHIVO 
        INFORMACION DEUDA ENTIDADES  TERRITORIALES AUTONOMAS .XLS
     **************************************************************/

    /*    public function sumatoriaDeudaTerritoriales($tasaDescuento, $codigo_entidad)
    {
        // Obtener 31 de diciembre del año pasado
        $diciembre31 = Carbon::now()->subYear()->endOfYear()->format('Y-m-d'); // 2023-12-31

        // Obtener 1 de enero del año en curso
        $enero1 = Carbon::now()->startOfYear()->format('Y-m-d'); // 2024-01-01

        $resultadoTotalFndr = DB::table('fndr_excel')
            ->selectRaw("
            ROUND(
                SUM(
                    (
                        CAST(interes AS NUMERIC) + 
                        CAST(interes_diferido AS NUMERIC) + 
                        CAST(capital AS NUMERIC) + 
                        CAST(capital_diferido AS NUMERIC)
                    ) / 
                    POWER(1 + ? / 365, 
                        (fecha_de_cuota::DATE - ?::DATE)
                    )
                ), 2
            ) AS resultado_total_fndr", [$tasaDescuento, $diciembre31])
            ->where('codigo_prsupuestario', $codigo_entidad)
            ->whereRaw("fecha_de_cuota::DATE >= ?", [$enero1])
            ->value('resultado_total_fndr');

        return $resultadoTotalFndr;
    } */


    public function sumatoriaDeudaTerritoriales($tasaDescuento, $codigo_entidad)
    {
        // Obtener 31 de diciembre del año pasado
        $diciembre31 = Carbon::now()->subYear()->endOfYear();
        $diciembre31Formateado = $diciembre31->format('d/m/Y'); // Ejemplo: 31/12/2023

        // Obtener 1 de enero del año en curso
        $enero1 = Carbon::now()->startOfYear();
        $enero1Formateado = $enero1->format('Y-m-d'); // Ejemplo: 2024-01-01

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
                        POWER(1 + $tasaDescuento  / 365, 
                            (TO_DATE('$diciembre31Formateado', 'DD/MM/YYYY') - TO_DATE(fecha_de_cuota, 'DD/MM/YYYY'))
                        )
                    ), 2
                ) AS resultado_total_fndr")
            ->where('codigo_prsupuestario', $codigo_entidad)
            ->whereRaw("TO_DATE(fecha_de_cuota, 'DD/MM/YYYY') >= TO_DATE('$enero1Formateado', 'YYYY-MM-DD')")
            ->value('resultado_total_fndr');

        return $resultadoTotalFndr;
    }


    /***************************************************************
        HALLAMOS  50% PASIVOS SIN CRONOGRAMAS
        DEL ARCHIVO 
     **************************************************************/
    public function cincuentaXcientoSinCronograma($codigo_entidad)
    {
        $totalSaldoConvertido = DB::table('balance_general_excel')
            ->selectRaw("
                ROUND(
                    SUM(
                        CASE 
                            WHEN saldo::NUMERIC < 0 THEN 0
                            ELSE saldo::NUMERIC
                        END
                    ) * 0.5, 2
                ) AS total_saldo_convertido
            ")
            ->where('entidad', $codigo_entidad)
            ->whereIn('cuenta', [
                '2111',
                '2112',
                '2113',
                '2114',
                '2115',
                '2116',
                '2118',
                '2119',
                '2121',
                '2122',
                '213',
                '2141',
                '2142',
                '2143',
                '2151',
                '2152',
                '2153',
                '2154',
                '216',
                '217',
                '2211',
                '2212',
                '2221',
                '2222',
                '224',
                '2251',
                '2252',
                '2253',
                '226'
            ])
            ->value('total_saldo_convertido');

        return $totalSaldoConvertido;
    }

    public function obtenerValorPresenteDeudaTotal($codigo_entidad)
    {
        $tasa = 0.0299;

        Log::debug('codigo_entidad' . $codigo_entidad);
        $valorPresenteDeuda = new ValorPresenteDeudaService();
        $deudaCreditoExterno = $valorPresenteDeuda->sumatoriaDeudaCreditoExterno($tasa, $codigo_entidad);


        Log::debug("deudaCreditoExterno" . $deudaCreditoExterno);

        $deudaCreditoTerritoriales = $valorPresenteDeuda->sumatoriaDeudaTerritoriales($tasa, $codigo_entidad);

        Log::debug("deudaCreditoTerritoriales" . $deudaCreditoTerritoriales);

        $pasivosSinCronogramas = $valorPresenteDeuda->cincuentaXcientoSinCronograma($codigo_entidad);

        Log::debug("pasivosSinCronogramas" . $pasivosSinCronogramas);

        $sumDeudas = $deudaCreditoExterno  +  $deudaCreditoTerritoriales + $pasivosSinCronogramas;

        Log::debug("sumDeudas" . $sumDeudas);

        // promedio icr eta
        // Subconsulta para el cálculo de promedio_icr_eta
        $promedioIcrEta = DB::table('icr_eta_rubro_total_excel')
            ->where('entidad', $codigo_entidad)
            ->where('nombre_total', 'ICR')
            ->selectRaw('ROUND(AVG(monto::DECIMAL), 2) AS promedio_icr_eta')
            ->first();

        //$resultadofinal = ($sumDeudas / $promedioIcrEta->promedio_icr_eta)*100;
        $resultadofinal = floor(($sumDeudas / $promedioIcrEta->promedio_icr_eta) * 100);

        Log::debug("resultadofinal" . $resultadofinal);

        return $resultadofinal;
    }
}
