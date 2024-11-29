<?php

namespace App\Imports;

use App\Models\DeudaPublicaExterna;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DeudaPublicaExternaImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function __construct()
    {
       // DeudaPublicaExterna::truncate();
    }
 
    public function model(array $row)
    {
        // valido si la fila tiene datos necesarios
        if (
            empty($row['prov_fondos']) ||
            empty($row['no_prestamos']) ||
            empty($row['no_tramos']) ||
            empty($row['nombre_del_acreedor']) ||
            empty($row['referencia_del_acreedor']) ||
            empty($row['fecha_de_firma']) ||
            empty($row['moneda_del_tramo']) ||
            empty($row['monto_del_tramo']) ||
            empty($row['monto_del_prestamo']) ||
            empty($row['plazo']) ||
            empty($row['tasa_de_interes']) ||
            empty($row['objeto']) ||
            empty($row['nombre'])
        ) {
            return null;
        }

        /* 
        return new DeudaPublicaExterna([
            'prov_fondos' => $row['prov_fondos'] ?? '',
            'no_prestamos' => $row['no_prestamos'] ?? '',
            'no_tramos' => $row['no_tramos'] ?? '',
            'nombre_del_acreedor' => $row['nombre_del_acreedor'] ?? '',
            'referencia_del_acreedor' => $row['referencia_del_acreedor'] ?? '',
            'fecha_de_firma' => $row['fecha_de_firma'] ?? '',
            'moneda_del_tramo' => $row['moneda_del_tramo'] ?? '',
            'monto_del_tramo' => $row['monto_del_tramo'] ?? '',
            'monto_del_prestamo' => $row['monto_del_prestamo'] ?? '',
            'plazo' => $row['plazo'] ?? '',
            'tasa_de_interes' => $row['tasa_de_interes'] ?? '',
            'objeto' => $row['objeto'] ?? '',
            'nombre' => $row['nombre'] ?? '',
            'situacion' => $row['situacion'] ?? '',
            'periodo_de_gracia' => $row['periodo_de_gracia'] ?? '',
        ]); */
    }
    /**
     * define que las cabeceras están en la fila 4 del archivo
     */
    public function headingRow(): int
    {
        return 4;
    }

    // especificar la hoja a importar
    public function sheets(): array
    {
        return [
            'INFORMACION GRAL.' => new DeudaPublicaExternaImport(),
        ];
    }
}
