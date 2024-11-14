<?php

namespace App\Imports;

use App\Models\FndrExcel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FndrExcelImport implements ToModel, WithHeadingRow
{
    public function __construct()
    {
        FndrExcel::truncate();
    }

    public function model(array $row)
    {
        Log::debug("imports ==>", $row);

       /*  if (
            empty($row['codigo_presupuestario']) 
        ) {
            return null;
        } */
       
        return new FndrExcel([
            'codigo_prsupuestario' => $row['codigo_prsupuestario'] ?? '',
            'entidad' => $row['entidad'] ?? '',
            'prestamo' => $row['prestamo'] ?? '',
            'programa' => $row['programa'] ?? '',
            'proyecto' => $row['proyecto'] ?? '',
            'monto_contratado' => $row['monto_contratado'] ?? '',
            'monto_prestamo' => $row['monto_prestamo'] ?? '',
            'fecha_desembolso' => $row['fecha_desembolso'] ?? '',
            'monto_desembolsado' => $row['monto_desembolsado'] ?? '',
            'plazo' => $row['plazo'] ?? '',
            'gracia' => $row['gracia'] ?? '',
            'fecha_de_vigencia' => $row['fecha_de_vigencia'] ?? '',
            'cuota' => $row['cuota'] ?? '',
            'fecha_de_cuota' => $row['fecha_de_cuota'] ?? '',
            'tasa_fecha_cuota' => $row['tasa_fecha_cuota'] ?? '',
            'capital' => $row['capital'] ?? '',
            'interes' => $row['interes'] ?? '',
            'capital_diferido' => $row['capital_diferido'] ?? '',
            'interes_diferido' => $row['interes_diferido'] ?? '',
            'cuentas_por_cobrar' => $row['cuentas_por_cobrar'] ?? '',
            'total_de_la_cuota' => $row['total_de_la_cuota'] ?? '',
            'estado_de_la_cuota' => $row['estado_de_la_cuota'] ?? '',
            'estado_del_prestamo' => $row['estado_del_prestamo'] ?? '',
            'moneda_del_prestamo' => $row['moneda_del_prestamo'] ?? '',
            'fecha_de_pago' => $row['fecha_de_pago'] ?? '',
            'saldo_de_capital_de_la_deuda' => $row['saldo_de_capital_de_la_deuda'] ?? '',
            'capital_amortizado' => $row['capital_amortizado'] ?? '',
            'interes_cobrado' => $row['interes_cobrado'] ?? '',
            'comisiones_cobradas' => $row['comisiones_cobradas'] ?? '',
        ]);
    }

    /**
     * define que las cabeceras están en la fila 4 del archivo
     */
    public function headingRow(): int
    {
        return 3;
    }

    // especificar la hoja a importar
    /*    public function sheets(): array
    {
        return [
            'Sheet 1' => new FndrExcel(),
        ];
    } */
}
