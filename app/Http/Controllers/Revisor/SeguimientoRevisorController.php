<?php

namespace App\Http\Controllers\Revisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Revisor\AsignarRevisoraRequest;
use App\Http\Services\Revisor\SeguimientoRevisorService;

class SeguimientoRevisorController extends Controller
{
    protected $seguimientoRevisorService;

    public function __construct(SeguimientoRevisorService $seguimientoRevisorService)
    {
        $this->seguimientoRevisorService = $seguimientoRevisorService;
    }

    public function index()
    {
        $response = $this->seguimientoRevisorService->obtenerSeguimientos();
        return response()->json($response['data'], $response['status']);
    }

    public function asignardeRevisoraJefeUnidad(AsignarRevisoraRequest $request)
    {
        $response = $this->seguimientoRevisorService->asignarRevisora($request);
        return response()->json($response['data'], $response['status']);
    }
}
