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

    public function obtenerDatosSolicitudes($solicitudId)
    {
        $response = $this->notaRiocpService->verNotas($solicitudId);
        return response()->json($response, $response['status'] ? 200 : 403);
    }
}
