<?php

namespace App\Http\Services\Excel;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PromedioIcrEtaImport;
use Illuminate\Support\Facades\Log;

class PromedioIcrEtaService
{

    public function importarArchivo($data)
    {
        Log::debug('Importar Archivo ====>' . $data['file']);

        try {
            $importer = new PromedioIcrEtaImport();
            $importer->loadFileAndProcess($data['file']);

            $processedData = $importer->getProcessedData();

            if (!empty($processedData)) {
                return [
                    'status' => 200,
                    'data' => $processedData,
                ];
            } else {
                return [
                    'status' => 404,
                    'data' => 'No se encontraron datos procesados.',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error al importar archivo: ' . $e->getMessage());
            return [
                'status' => 500,
                'data' => 'Error al importar archivo: ' . $e->getMessage(),
            ];
        }
    }


    /*  public function importarArchivo($data)
    {
        Log::debug('Importar Archivo ====>' . $data['file']);
        try {
            $result = Excel::toArray(new PromedioIcrEtaImport, $data['file']);
            $array = array_filter($result[0][0], function ($value) {
                return is_int($value); // Filtra solo los números enteros
            });

            if (!empty($result) && !empty($result[0])) {

                return [
                    'status' => 200,
                    'data' => $array = array_values($array),
                ];
            } else {
                return [
                    'status' => 404,
                    'data' => 'No se encontraron datos en la primera fila.',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error al importar archivo: ' . $e->getMessage());
            return [
                'status' => 500,
                'data' => 'Error al importar archivo: ' . $e->getMessage(),
            ];
        }
    }
 */
    /*     public function importarArchivo($data)
    {
        Log::debug('Importar Archivo ====>' . $data['file']);
        try {
            $result = Excel::toArray(new PromedioIcrEtaImport, $data['file']);
            $array = array_filter($result[0][0], function ($value) {
                return is_int($value); // Filtra solo los números enteros
            });

            if (!empty($result) && !empty($result[0])) {

                return [
                    'status' => 200,
                    'data' => $array = array_values($array),
                ];
            } else {
                return [
                    'status' => 404,
                    'data' => 'No se encontraron datos en la primera fila.',
                ];
            }

        } catch (\Exception $e) {
            Log::error('Error al importar archivo: ' . $e->getMessage());
            return [
                'status' => 500,
                'data' => 'Error al importar archivo: ' . $e->getMessage(),
            ];
        }
    } */
}
