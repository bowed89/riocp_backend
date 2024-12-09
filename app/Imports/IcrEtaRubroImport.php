<?php

namespace App\Imports;

use App\Models\IcrEtaEpreExcel;
use App\Models\IcrEtaEpreTotalExcel;
use App\Models\IcrEtaRubroExcel;
use App\Models\IcrEtaRubroTotalExcel;
use Maatwebsite\Excel\Concerns\ToArray;
use PhpOffice\PhpSpreadsheet\IOFactory;

class IcrEtaRubroImport implements ToArray
{
    public function __construct()
    {
        IcrEtaRubroExcel::truncate();
        IcrEtaRubroTotalExcel::truncate();
        IcrEtaEpreExcel::truncate();
        IcrEtaEpreTotalExcel::truncate();
    }


    public function loadFileAndProcess($filePath)
    {

        try {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();

            /* ALMACENAR RUBRO */
            // Rango de las filas.
            $startRow = 4;
            $endRow = 21;
            // obtengo el array con los datos
            $rubro = $this->iterateArray($sheet, $startRow, $endRow);
            // almaceno el array con los datos
            $this->storeRubro($rubro);

            /* ALMACENAR TOTAL RUBRO */
            // Rango de las filas.
            $startRow = 22;
            $endRow = 25;
            // obtengo el array con los datos
            $totalRubro = $this->iterateArray($sheet, $startRow, $endRow);
            // almaceno el array con los datos
            $this->storeTotalRubro($totalRubro);

            /* ALMACENAR EPRE */
            // Rango de las filas.
            $startRow = 28;
            $endRow = 49;
            // obtengo el array con los datos
            $epre = $this->iterateArray($sheet, $startRow, $endRow);
            // almaceno el array con los datos
            $this->storeEpre($epre);

            /* ALMACENAR TOTAL EPRE */
            // Rango de las filas.
            $startRow = 50;
            $endRow = 50;
            // obtengo el array con los datos
            $totalEpre = $this->iterateArray($sheet, $startRow, $endRow);
            // almaceno el array con los datos
            $this->storeTotalEpre($totalEpre);

            return 200;

        } catch (\Exception $e) {
            // Capturar errores y retornar el mensaje de error
            return 'Error durante el procesamiento: ' . $e->getMessage();
        }


        //  $this->processedData = $rubro;
    }

    private function iterateArray($sheet, $startRow, $endRow)
    {
        $result = [];
        $auxFilaEntidad = null;
        // Columnas inicio
        $inicioColumn = 'B';
        $inicioColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($inicioColumn);

        // Columnas fin 
        $finColumn = $sheet->getHighestColumn(); // obtengo la ultima columna
        $finColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($finColumn);

        // Columna fija de rubros
        $columnFijaRubro = 'A';

        // Fila fija para entidad
        $filaFijaEntidad = 1;

        // Fila fija para gestion
        $filaFijaGestion = 3;

        // recorro las columnas
        for ($col = $inicioColumnIndex; $col <= $finColumnIndex; $col++) {
            // Convertir el índice de columna a letra
            $conversionColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            // $conversionColumnfija = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col[0]);

            $filaGestion = $sheet->getCell("$conversionColumn$filaFijaGestion")->getValue();
            $filaEntidad = $sheet->getCell("$conversionColumn$filaFijaEntidad")->getValue();

            // en ocasiones entidad obtiene null, en ese caso almaceno en un aux los valores q no son null...
            if ($filaEntidad !== null) {
                $auxFilaEntidad = $filaEntidad;
            }

            // recorro las filas
            for ($row = $startRow; $row <= $endRow; $row++) {
                //  valores de las celdas
                $columnFija = $sheet->getCell("$columnFijaRubro$row")->getValue() ?? 'EPRE 41 119 (19211) + EPRE 41 119 (19212)';
                $columnaAvanza = $sheet->getCell("$conversionColumn$row")->getValue();

                // agregar el par de datos como un subarray
                $result[] = [$auxFilaEntidad, $filaGestion, $columnFija, $columnaAvanza];
            }
        }

        // eliminar valores nulos de la matriz
        $deleteNulls = $this->deleteNullsArray($result);

        return $deleteNulls;
    }

    // verificoo si hay valores null estrictamente para borrarlos
    private function deleteNullsArray($array)
    {
        return  array_filter($array, function ($subArray) {
            return !in_array(null, $subArray, true);
        });
    }

    private function storeRubro($arrays)
    {
        try {
            foreach ($arrays as $array) {
                IcrEtaRubroExcel::create([
                    'gestion' => $array[0],
                    'entidad' => $array[1],
                    'rubro' => $array[2],
                    'monto' => $array[3],
                ]);
            }
        } catch (\Exception $e) {
            echo "Error al guardar los datos: " . $e->getMessage();
        }
    }

    private function storeTotalRubro($arrays)
    {
        try {
            foreach ($arrays as $array) {
                IcrEtaRubroTotalExcel::create([
                    'entidad' => $array[0],
                    'gestion' => $array[1],
                    'nombre_total' => $array[2],
                    'monto' => $array[3],
                ]);
            }
        } catch (\Exception $e) {
            echo "Error al guardar los datos: " . $e->getMessage();
        }
    }

    private function storeEpre($arrays)
    {
        try {
            foreach ($arrays as $array) {
                IcrEtaEpreExcel::create([
                    'entidad' => $array[0],
                    'gestion' => $array[1],
                    'epre' => $array[2],
                    'monto' => $array[3],
                ]);
            }
        } catch (\Exception $e) {
            echo "Error al guardar los datos: " . $e->getMessage();
        }
    }

    private function storeTotalEpre($arrays)
    {
        try {
            foreach ($arrays as $array) {
                IcrEtaEpreTotalExcel::create([
                    'entidad' => $array[0],
                    'gestion' => $array[1],
                    'nombre_total' => $array[2],
                    'monto' => $array[3],
                ]);
            }
        } catch (\Exception $e) {
            echo "Error al guardar los datos: " . $e->getMessage();
        }
    }

    public function array(array $rows) {}
}
