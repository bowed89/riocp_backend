<?php

namespace App\Http\Controllers\Revisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Revisor\SubirHistorialRequests;
use App\Http\Services\Revisor\SubirHistorialExcelService;

class SubirHistorialExcelController extends Controller
{
    protected $subirHistorialService;

    public function __construct(SubirHistorialExcelService $subirHistorialService)
    {
        $this->subirHistorialService = $subirHistorialService;
    }

    public function subirDocumento(SubirHistorialRequests $request)
    {

        $response = $this->subirHistorialService->crearNuevoHistorial($request);
        return response()->json([
            'status' => $response['status'],
            'message' => $response['message'],
            'data' => $response['data'] ?? null,
        ], $response['code']);
    }
}
