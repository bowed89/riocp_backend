<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Excel\DeudaPublicaExternaRequest;
use App\Http\Services\Excel\IcrEtaExcelService;
use App\Http\Services\Excel\PromedioIcrEtaService;

class PromedioIcrEtaController extends Controller
{
    protected $icrEtaService;

    public function __construct(IcrEtaExcelService $icrEtaService)
    {
        $this->icrEtaService = $icrEtaService;
    }

    public function icrEtaExcel(DeudaPublicaExternaRequest $request)
    {
        $resultado = $this->icrEtaService->importarArchivo($request);

        return response()->json($resultado['message'], $resultado['status']);

    }
}
