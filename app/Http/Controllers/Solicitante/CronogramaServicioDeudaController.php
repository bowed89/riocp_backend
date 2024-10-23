<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solicitante\CronogramaServicioDeudaRequest;
use App\Http\Services\Solicitante\CronogramaServicioDeudaService;
use Illuminate\Http\Request;

class CronogramaServicioDeudaController extends Controller
{
    protected $cronogramaServicioDeudaService;

    public function __construct(CronogramaServicioDeudaService $cronogramaServicioDeudaService)
    {
        $this->cronogramaServicioDeudaService = $cronogramaServicioDeudaService;
    }

    public function storeCronogramaServicioDeuda(CronogramaServicioDeudaRequest $request)
    {
        $result = $this->cronogramaServicioDeudaService->createCronogramaServicioDeuda($request->validated());
        return response()->json($result, $result['status'] ? 200 : 400);
    }


    public function getCronogramaById($id)
    {
        $result = $this->cronogramaServicioDeudaService->getCronogramaCuadrosById($id);
        return response()->json($result, $result['status'] ? 200 : 400);
    }
}
