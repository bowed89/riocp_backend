<?php

namespace App\Imports;

use App\Models\IcrEtaEpreExcel;
use App\Models\IcrEtaEpreTotalExcel;
use App\Models\IcrEtaRubroExcel;
use App\Models\IcrEtaRubroTotalExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;
use PhpOffice\PhpSpreadsheet\IOFactory;

class IcrEtaRubroImport implements ToArray
{
    public function __construct()
    {
        ini_set('memory_limit', '7024M'); // 1 GB

        /* IcrEtaRubroExcel::truncate();
        IcrEtaRubroTotalExcel::truncate();
        IcrEtaEpreExcel::truncate();
        IcrEtaEpreTotalExcel::truncate();
         */
    }


    public function loadFileAndProcess($filePath)
    {
        try {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $arrayAgrupado = $this->agruparPorEntidades($sheet);
            // $quitarPuntos = $this->quitarPuntosDeRubro($arrayAgrupado);
            $agruparPorAnios = $this->agruparPorUltimos4Anios($arrayAgrupado);

            // Filtrar solo los elementos que contengan "11"por entidad

            foreach ($agruparPorAnios as $value) {
                Log::debug("agruparPorAnios: " . json_encode($value));
            }


            return 200;
        } catch (\Exception $e) {
            // Capturar errores y retornar el mensaje de error
            return 'Error durante el procesamiento: ' . $e->getMessage();
        }
        //  $this->processedData = $rubro;
    }

    private function agruparPorEntidades($sheet)
    {

        /* 
            [
                [
                    ["138", "...", "...", "..."],
                    ["138", "...", "...", "..."]
                ],
                [
                    ["139", "...", "...", "..."],
                    ["139", "...", "...", "..."]
                ],
                ..................................
            ]    
        */

        $result = [];
        // Obtener la última fila con datos
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Crear un array auxiliar para agrupar las filas por código de entidad
        $groupedByEntidad = [];

        // Recorrer todas las filas desde la segunda
        for ($row = 2; $row <= $highestRow; $row++) {
            // Obtener el valor de la columna 'A' (código de entidad) de la fila actual
            $entidadCodigo = $sheet->getCell("A$row")->getValue();
            // Borrar rubro que tiene string

            // Obtener los datos de la fila actual
            $rowData = $sheet->rangeToArray("A$row:$highestColumn$row", null, true, false)[0];

            // Agrupar los datos según el código de entidad
            if (!isset($groupedByEntidad[$entidadCodigo])) {
                // Crear un nuevo grupo si no existe
                $groupedByEntidad[$entidadCodigo] = [];
            }

            // Añadir la fila actual al grupo correspondiente
            $groupedByEntidad[$entidadCodigo][] = $rowData;
        }

        // Convertir los grupos a un array de arrays
        foreach ($groupedByEntidad as $grupo) {
            $result[] = $grupo;
        }

        // Quitar los puntos de la columna RUBROS(ej. 12.1.2 a 1212)
        return $result;
    }

    private function quitarPuntosDeRubro($arrays)
    {
        $newArray = [];
        foreach ($arrays as $res) {
            for ($i = 0; $i < count($res); $i++) {
                $rubro = str_replace('.', '', $res[$i][4]);
                $res[$i][4] = $rubro;
            }
            $newArray[] = $res;
        }

        return $newArray;
    }

    private function agruparPorUltimos4Anios($arrays)
    {
        $ultimosCuatroAnios = $this->obtener4UltimosAnios();
        // Array para agrupar por entidades
        $groupedByEntidad = [];

        // Iterar sobre el array principal
        foreach ($arrays as $res) {
            for ($i = 0; $i < count($res); $i++) {
                $gestion = $res[$i][16]; // Cambiar al índice correcto para "gestión"
                $entidadCodigo = $res[$i][0]; // Cambiar al índice correcto para "entidad"

                foreach ($ultimosCuatroAnios as $anio) {
                    if ($gestion == $anio) {

                        // Si no existe un grupo para esta entidad, lo inicializamos
                        if (!isset($groupedByEntidad[$entidadCodigo])) {
                            $groupedByEntidad[$entidadCodigo] = [];
                        }

                        // Añadimos la fila correspondiente al grupo de esta entidad
                        $groupedByEntidad[$entidadCodigo][] = $res[$i];
                    }
                }
            }
        }

        // Convertir a un array de arrays para devolver el formato que necesitas
        $result = array_values($groupedByEntidad);

        return $result;
    }

    private function filtrarPorRubro($arrays)
    {
        $ultimosCuatroAnios = $this->obtener4UltimosAnios();

        foreach ($arrays as $res) {
            for ($i = 0; $i < count($res); $i++) {

            }
        }
    }

    private function listadoRubros() {
        return [
            "11.","12.","13.","14.","15.","16.","17."
        ];
    }

    private function obtener4UltimosAnios()
    {
        // Obtener el año actual
        $anioActual = Carbon::now()->year;
        // Obtener los últimos 4 años
        $ultimosCuatroAnios = [];
        for ($i = 1; $i <= 4; $i++) {
            $ultimosCuatroAnios[] = $anioActual - $i;
        }

        return $ultimosCuatroAnios;
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
