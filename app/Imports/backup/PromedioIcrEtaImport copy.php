<?php

namespace App\Imports;

use App\Models\PromedioIcrEta;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToArray;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PromedioIcrEtaImport implements ToArray
{
    public function __construct()
    {
     //   PromedioIcrEta::truncate();
    }


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

        // Rango de las filas
        $startRow = 3;
        $endRow = 50;

        // Rango de las columnas 
        $startColumn = 'B'; // index = 2
        $endColumn = $sheet->getHighestColumn(); // obtengo la ultima columna

        // Convertir columnas de letras a números
        $startColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($startColumn);
        $endColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($endColumn);
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
                if ($rowData !== null && !is_string($rowData)) {
                    $currentRowData[] = $rowData;
                }
            }

            $data[] = $currentRowData;
        }

        // Log::debug("Data ===>" .json_encode($data));
        $this->store($data);

        $this->processedData = $data;
    }
    // Almacenar en la base de datos el array armado...
    private function store($arrays)
    {
        Log::debug("count ===>" . json_encode(count($arrays)));
        try {
            foreach ($arrays as $array) {
                if (count($array) >= 47) {

                    $data = [
                        'entidad' => $array[0],
                        'gestion' => $array[1],
                        '11000' => $array[2],
                        '12000' => $array[3],
                        '13000' => $array[4],
                        '14000' => $array[5],
                        '15000' => $array[6],
                        '16000' => $array[7],
                        '17000' => $array[8],
                        '19211' => $array[9],
                        '19212' => $array[10],
                        '19216' => $array[11],
                        '19219' => $array[12],
                        '19220' => $array[13],
                        '19230' => $array[14],
                        '19260' => $array[15],
                        '19270' => $array[16],
                        '19280' => $array[17],
                        '19300' => $array[18],
                        '19400' => $array[19],
                        'total' => $array[20],
                        '19212_org_119_idh' => $array[21],
                        '19212_org_119_50_percent' => $array[22],
                        'icr' => $array[23],
                        'epre_19212' => $array[24],
                        'epre_41_119_19211' => $array[25],
                        'epre_41_119_19212' => $array[26],
                        'sumatoria_epre_19211_19212' => $array[27],
                        'epre_19216' => $array[28],
                        'epre_41_119_19216' => $array[29],
                        'epre_19219' => $array[30],
                        'epre_41_119_19219' => $array[31],
                        'epre_19220' => $array[32],
                        'epre_41_119_19220' => $array[33],
                        'epre_19230' => $array[34],
                        'epre_41_119_19230' => $array[35],
                        'epre_19260' => $array[36],
                        'epre_41_119_19260' => $array[37],
                        'epre_19270' => $array[38],
                        'epre_41_119_19270' => $array[39],
                        'epre_19280' => $array[40],
                        'epre_41_119_19280' => $array[41],
                        'epre_19300' => $array[42],
                        'epre_41_119_19300' => $array[43],
                        'epre_19400' => $array[44],
                        'epre_41_119_19400' => $array[45],
                        'total_41_119' => $array[46]
                    ];

                //   PromedioIcrEta::create($data);
                }
            }
        } catch (\Exception $e) {
            echo "Error al guardar los datos: " . $e->getMessage();
        }
    }

    public function array(array $rows) {}
}
