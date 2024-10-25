<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operador\ObservacionTecnicoRequest;
use App\Http\Services\Operador\ObservacionTecnicoService;

class ObservacionTecnicoController extends Controller
{
    protected $observacionTecnicoService;

    public function __construct(ObservacionTecnicoService $observacionTecnicoService)
    {
        $this->observacionTecnicoService = $observacionTecnicoService;
    }

    public function index()
    {
        $response = $this->observacionTecnicoService->verTipoObservaciones();
        return response()->json($response['data'], $response['status']);
    }

    public function store(ObservacionTecnicoRequest $request)
    {
        $response = $this->observacionTecnicoService->guardarObservaciones($request);
        return response()->json($response['data'], $response['status']);
    }
}
