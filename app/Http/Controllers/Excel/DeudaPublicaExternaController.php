<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Excel\DeudaPublicaExternaRequest;
use App\Http\Services\Excel\DeudaPublicaExternaService;

class DeudaPublicaExternaController extends Controller
{
    protected $deudaPublicaExternaService;

    public function __construct(DeudaPublicaExternaService $deudaPublicaExternaService)
    {
        $this->deudaPublicaExternaService = $deudaPublicaExternaService;
    }

    public function deudaPublicaExterna(DeudaPublicaExternaRequest $request)
    {
        $resultado = $this->deudaPublicaExternaService->importarArchivo($request);
        return response()->json($resultado['message'], $resultado['status']);
    }
}
