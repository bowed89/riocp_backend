<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solicitante\CronogramaDesembolsoRequest;
use App\Http\Services\Solicitante\CronogramaDesembolsoProgramadoService;

class CronogramaDesembolsoProgramadoController extends Controller
{
    protected $cronogramaDesembolsoProgramadoService;

    public function __construct(CronogramaDesembolsoProgramadoService $cronogramaDesembolsoProgramadoService)
    {
        $this->cronogramaDesembolsoProgramadoService = $cronogramaDesembolsoProgramadoService;
    }

    public function storeCronogramaDesembolso(CronogramaDesembolsoRequest $request)
    {
        $result = $this->cronogramaDesembolsoProgramadoService->createCronogramaDesembolso($request->validated());
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function getCronogramaDesembolso($id) {
        $result = $this->cronogramaDesembolsoProgramadoService->getCronogramaDesembolsoById($id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }
}
