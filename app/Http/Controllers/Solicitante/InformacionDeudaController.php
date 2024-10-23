<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solicitante\InformacionDeudaRequest;
use App\Http\Services\Solicitante\InformacionDeudaService;

class InformacionDeudaController extends Controller
{
    protected $informacionDeudaService;

    public function __construct(InformacionDeudaService $informacionDeudaService)
    {
        $this->informacionDeudaService = $informacionDeudaService;
    }

    public function storeSolicitudInformacionDeuda(InformacionDeudaRequest $request)
    {
        $result = $this->informacionDeudaService->createInformacionDeuda($request->validated());

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $result['data'] ?? [],
            'errors' => $result['errors'] ?? []
        ], $result['code']);
    }

    public function getInformacionById($id)
    {
        $result = $this->informacionDeudaService->getInformacionDeudaById($id);
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $result['data'] ?? [],
            'errors' => $result['errors'] ?? []
        ], $result['code']);
    }
}
