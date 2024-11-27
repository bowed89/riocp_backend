<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operador\AsignarOperadorRevisorRequest;
use App\Http\Services\Operador\SeguimientoService;

class SeguimientoOperadorController extends Controller
{
    protected $seguimientoService;

    public function __construct(SeguimientoService $seguimientoService)
    {
        $this->seguimientoService = $seguimientoService;
    }

    public function index()
    {
        $response = $this->seguimientoService->listarSeguimientos();
        return response()->json($response, $response['status'] ? 200 : 403);
    }

    public function asignardeOperadoraRevisor(AsignarOperadorRevisorRequest $request)
    {
        $response = $this->seguimientoService->asignarSeguimiento($request->validated());

        return response()->json($response, $response['status'] ? 200 : 400);
    }
}


// notas:
// id, referencia, body, nro_hoja_ruta, solicitud_riocp_id