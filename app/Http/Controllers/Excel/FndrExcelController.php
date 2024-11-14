<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Excel\DeudaPublicaExternaRequest;
use App\Http\Services\Excel\FndrExcelService;

class FndrExcelController extends Controller
{
    protected $fndrExcelService;

    public function __construct(FndrExcelService $fndrExcelService)
    {
        $this->fndrExcelService = $fndrExcelService;
    }

    public function fndrExcel(DeudaPublicaExternaRequest $request)
    {
        $resultado = $this->fndrExcelService->importarArchivo($request);
        return response()->json($resultado['message'], $resultado['status']);
    }
}
