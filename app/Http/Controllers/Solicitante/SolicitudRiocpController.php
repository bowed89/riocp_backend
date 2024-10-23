<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solicitante\SolicitudRiocpRequest;
use App\Http\Services\Solicitante\SolicitudRiocpService;

class SolicitudRiocpController extends Controller
{
    protected $solicitudRiocpService;

    public function __construct(SolicitudRiocpService $solicitudRiocpService)
    {
        $this->solicitudRiocpService = $solicitudRiocpService;
    }

    public function index()
    {
        return $this->solicitudRiocpService->getAllSolicitudes();
    }

    public function getAllSolicitudesById($id)
    {
        return $this->solicitudRiocpService->getSolicitudesById($id);
    }

    public function storeSolicitudFormularioRiocp(SolicitudRiocpRequest $request)
    {
        $result = $this->solicitudRiocpService->createSolicitudRiocp($request->validated());

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $result['data'] ?? [],
            'errors' => $result['errors'] ?? []
        ], $result['code']);
    }
}
