<?php

namespace App\Imports;

use App\Models\BalanceGeneralExcel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BalanceGeneralExcelImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function __construct()
    {
        BalanceGeneralExcel::truncate();
        ini_set('max_execution_time', 300); // 300 segundos (5 minutos)

    }

    public function model(array $row)
    {
        if (
            empty($row['gestion']) ||
            empty($row['sistema_eeff']) ||
            empty($row['nivel_institucional']) ||
            empty($row['desc_estructura']) ||
            empty($row['entidad']) ||
            empty($row['desc_entidad']) ||
            empty($row['cuenta']) ||
            empty($row['desc_cuenta']) ||
            empty($row['imputable']) ||
            empty($row['saldo'])
        ) {
            return null;
        }

        return new BalanceGeneralExcel([
            'gestion' => $row['gestion'] ?? '',
            'sistema_eeff' => $row['sistema_eeff'] ?? '',
            'nivel_institucional' => $row['nivel_institucional'] ?? '',
            'desc_estructura' => $row['desc_estructura'] ?? '',
            'entidad' => $row['entidad'] ?? '',
            'desc_entidad' => $row['desc_entidad'] ?? '',
            'cuenta' => $row['cuenta'] ?? '',
            'desc_cuenta' => $row['desc_cuenta'] ?? '',
            'imputable' => $row['imputable'] ?? '',
            'saldo' => $row['saldo'] ?? '',
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Define el tamaño de cada lote de datos a procesar.
     */
    public function chunkSize(): int
    {
        return 1000; // Procesa el archivo en lotes de 1000 filas
    }
}
