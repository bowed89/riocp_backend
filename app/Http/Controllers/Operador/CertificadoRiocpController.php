<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operador\CertificadoRiocpRequest;
use App\Http\Services\Operador\CertificadoRiocpService;

class CertificadoRiocpController extends Controller
{
    protected $certificadoService;

    public function __construct(CertificadoRiocpService $certificadoService)
    {
        $this->certificadoService = $certificadoService;
    }

    public function obtenerDatosSolicitudes($idSolicitud)
    {
        $response = $this->certificadoService->obtenerSolicitudCertificado($idSolicitud);
        return response()->json($response, $response['status'] ? 200 : 403);
    }

    public function almacenarCertificadoAprobado(CertificadoRiocpRequest $request)
    {
        $response = $this->certificadoService->almacenarCertificadoAprobado($request->validated());
        return response()->json($response, $response['status'] ? 200 : 403);
    }
}
