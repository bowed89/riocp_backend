<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrador\SeguimientoJefeUnidadRequest;
use App\Http\Services\Administrador\SeguimientoJefeUnidadService;

class SeguimientoJefeUnidadController extends Controller
{
    protected $seguimientoService;

    public function __construct(SeguimientoJefeUnidadService $seguimientoService)
    {
        $this->seguimientoService = $seguimientoService;
    }

    public function index()
    {
        $response = $this->seguimientoService->listarSeguimientos();
        return response()->json($response, $response['status'] ? 200 : 403);
    }

    public function asignarTecnicoRevisor(SeguimientoJefeUnidadRequest $request)
    {
        $response = $this->seguimientoService->asignarTecnicoRevisor($request->validated());
        return response()->json($response, $response['status'] ? 200 : 400);
    }
}
