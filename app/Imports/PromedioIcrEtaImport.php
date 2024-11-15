<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PromedioIcrEtaImport implements ToArray
{

    private $processedData = [];

    public function getProcessedData()
    {
        return $this->processedData;
    }


    public function loadFileAndProcess($filePath)
    {
        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Rango de datos a extraer
        /*   $startRow = 3;
        $endRow = 8; */
        $startRow = 4;
        $endRow = 21;

        // Rango de columnas a recorrer
        $startColumn = 'B';  // Columna B
        $endColumn = 'E';    // Columna E (puedes cambiar el final si necesitas más columnas)

        // Convertir columnas de letras a números
        $startColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($startColumn) - 1;
        $endColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($endColumn) - 1;


        // Crear un array para almacenar los datos
        $data = [];

        // Iterar sobre las filas y columnas para extraer los datos
        for ($row = $startRow; $row <= $endRow; $row++) {
            $rowData = $sheet->getCell("B$row")->getValue() ?? 0.00;

            /*   for ($col = $startColumn; $col <= $endColumn; $col++) {
                $cellValue = $sheet->getCell("$col$row")->getValue();
                $rowData[] = $cellValue ?? 0.00; // Añadir el valor o 0.00 si está vacío
            } */

            $data[] = $rowData;

            // Iterar sobre las columnas (de B a E)
            for ($col = $startColumnIndex; $col <= $endColumnIndex; $col++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1); // Convertir el índice de columna a letra
                $cellValue = $sheet->getCell("$columnLetter$row")->getValue() ?? 0.00;
                $rowData[] = $cellValue; // Añadir el valor de la celda al array de fila
            }
        }

        $this->processedData = $data;
    }

    public function array(array $rows)
    {
        // Este método es obligatorio, pero no lo estás utilizando
    }

    /*   public function array(array $rows)
    {
        Log::debug('Dentro de debug: ' . json_encode($rows));
        return $rows[0]; 
    } */
}
