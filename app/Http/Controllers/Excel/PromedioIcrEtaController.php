<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Excel\DeudaPublicaExternaRequest;
use App\Http\Services\Excel\PromedioIcrEtaService;

class PromedioIcrEtaController extends Controller
{
    protected $icrEtaService;

    public function __construct(PromedioIcrEtaService $icrEtaService)
    {
        $this->icrEtaService = $icrEtaService;
    }

    public function icrEtaExcel(DeudaPublicaExternaRequest $request)
    {
        $resultado = $this->icrEtaService->importarArchivo($request);

        return response()->json(
            [
                'data' => $resultado['data']
            ],
            $resultado['status'] // Aquí pasamos el código de estado
        );

        /*    return response()->json(
            $resultado['status'], 
            $resultado['data']
        ); */
    }
}
