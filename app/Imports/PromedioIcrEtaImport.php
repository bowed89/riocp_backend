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

        $highestColumn = $sheet->getHighestColumn(); // OBTENER LA ULTIMA COLUMNA BDD

        // Rango de las filas
        $startRow = 3;
        $endRow = 50;

        // Rango de las columnas 
        $startColumn = 'B'; // index = 2
        $endColumn = 'E'; // index = 3

        // Convertir columnas de letras a números
        $startColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($startColumn);
        $endColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        $auxRowEntidad = null;

        $data = [];

        // recorro las columnass
        for ($col = $startColumnIndex; $col <= $endColumnIndex; $col++) {
            // Convertir el indice de columna a letra
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            
            // obtener las id entidades de la primera fila 
            $rowFilaUno = 1; //fila 1 donde estan los ids
            $rowEntidadesID = $sheet->getCell("$columnLetter$rowFilaUno")->getValue();

            if ($rowEntidadesID !== null) {
                $auxRowEntidad = $rowEntidadesID;
            } else {
                $rowEntidadesID = $auxRowEntidad;
            }


            $currentRowData = [];
            $currentRowData[] = $rowEntidadesID; // concateno id entidades en cada array

            // Recorro las filas
            for ($row = $startRow; $row <= $endRow; $row++) {
                $rowData = $sheet->getCell("$columnLetter$row")->getValue();

                if($rowData !== null && !is_string($rowData) ) {
                    $currentRowData[] = $rowData;
                }
            }
            $data[] = $currentRowData;
        }
        
        foreach ($data as $subArray) {
            Log::debug("subArray ====> " . json_encode($subArray));

            

        
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
