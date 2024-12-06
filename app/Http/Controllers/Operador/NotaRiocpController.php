<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Http\Services\Operador\NotaRiocpService;

class NotaRiocpController extends Controller
{
    protected $notaRiocpService;

    public function __construct(NotaRiocpService $notaRiocpService)
    {
        $this->notaRiocpService = $notaRiocpService;
    }

    public function obtenerDatosNotaRechazo($solicitudId)
    {
        $response = $this->notaRiocpService->verNotaRechazo($solicitudId);
        return response()->json($response, $response['status'] ? 200 : 403);
    }
    public function obtenerDatosNotaAprobacion($solicitudId)
    {
        $response = $this->notaRiocpService->verNotaAprobado($solicitudId);
        return response()->json($response, $response['status'] ? 200 : 403);
    }
    public function obtenerDatosNotaObervacion($solicitudId)
    {
        $response = $this->notaRiocpService->verNotaObservacion($solicitudId);
        return response()->json($response, $response['status'] ? 200 : 403);
    }
}
