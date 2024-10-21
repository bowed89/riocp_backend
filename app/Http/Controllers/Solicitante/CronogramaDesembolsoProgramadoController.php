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

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'errors' => $result['errors'] ?? [],
        ], $result['code']);
    }
}
