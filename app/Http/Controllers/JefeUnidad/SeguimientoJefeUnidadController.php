<?php

namespace App\Http\Controllers\JefeUnidad;

use App\Http\Controllers\Controller;
use App\Http\Requests\JefeUnidad\SeguimientoJefeUnidadRequest;
use App\Http\Services\JefeUnidad\SeguimientoJefeUnidadService;

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

    public function contadorAsignado() 
    {
        $response = $this->seguimientoService->countDerivado();
        return response()->json($response, $response['status'] ? 200 : 400);
    }
}
