<?php

namespace App\Imports;

use App\Models\CronogramaDeudaPublicaExterna;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CronogramaDeudaPublicaExternaImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function __construct()
    {
        //CronogramaDeudaPublicaExterna::truncate();
    }

    public function model(array $row)
    {
        // valido dsi la fila tiene datos necesarios
        if (
            empty($row['no_prestamos']) ||
            empty($row['no_tramos']) 
        ) {
            return null;
        }
       /*  return new CronogramaDeudaPublicaExterna([
            'no_prestamos' => $row['no_prestamos'] ?? null,
            'no_tramos' => $row['no_tramos'] ?? null,
            'prov_fondos' => $row['prov_fondos'] ?? null,
            
            'moneda_del_tramo' => $row['moneda_del_tramo'] ?? null,
            'nombre_del_acreedor' => $row['nombre_del_acreedor'] ?? null,
            'concepto' => $row['concepto'] ?? null,
            'moneda' => $row['moneda'] ?? null,
            'fecha_de_vencimiento' => $row['fecha_de_vencimiento'] ?? null,
            'saldo_adeudado_al_31_12_2022' => $row['saldo_adeudado_al_31_12_2022'] ?? null,
        ]); */


       /*  for ($year = 2023; $year <= 2059; $year++) {
            $prefix = "{$year}/";
            $deudaPublica->fill([
                "{$prefix}1" => $row["{$prefix}1"] ?? null,
                "{$prefix}2" => $row["{$prefix}2"] ?? null,
            ]);
        } */
    }

    public function headingRow(): int
    {
        return 5;
    }

    public function sheets(): array
    {
        return [
            'PERFIL DE VCTOS.' => new CronogramaDeudaPublicaExternaImport(),
        ];
    }
}
