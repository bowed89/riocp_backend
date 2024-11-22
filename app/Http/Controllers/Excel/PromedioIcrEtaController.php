<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Excel\DeudaPublicaExternaRequest;
use App\Http\Services\Excel\IcrEtaExcelService;
use Illuminate\Support\Facades\Log;

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

        Log::debug("message ===>" .json_encode($resultado));


        return response()->json($resultado, $resultado['status']);

    }
}
