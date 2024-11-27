<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operador\CertificadoRiocpRequest;
use App\Http\Services\Operador\NotaRiocpService;

class NotaRiocpController extends Controller
{
    protected $notaRiocpService;

    public function __construct(NotaRiocpService $notaRiocpService)
    {
        $this->notaRiocpService = $notaRiocpService;
    }

    public function obtenerDatosSolicitudes($solicitudId, $sd, $vpd)
    {
        $response = $this->notaRiocpService->verNotas($solicitudId, $sd, $vpd);
        return response()->json($response, $response['status'] ? 200 : 403);
    }
}
