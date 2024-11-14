<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Excel\DeudaPublicaExternaRequest;
use App\Http\Services\Excel\BalanceGeneralExcelService;

class BalanceGeneralExcelController extends Controller
{
    protected $balanceGeneralExcelService;

    public function __construct(BalanceGeneralExcelService $balanceGeneralExcelService)
    {
        $this->balanceGeneralExcelService = $balanceGeneralExcelService;
    }

    public function balanceGeneralExcel(DeudaPublicaExternaRequest $request)
    {
        $resultado = $this->balanceGeneralExcelService->importarArchivo($request);
        return response()->json($resultado['message'], $resultado['status']);
    }
}
