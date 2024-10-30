<?php

namespace App\Http\Controllers\Revisor;

use App\Http\Controllers\Controller;
use App\Http\Services\Revisor\ObservacionRevisorService;

class ObservacionRevisorController extends Controller
{
    protected $observacionRevisorService;

    public function __construct(ObservacionRevisorService $observacionRevisorService)
    {
        $this->observacionRevisorService = $observacionRevisorService;
    }

    public function verObservacionIdSolicitud($solicitudId)
    {
        $response = $this->observacionRevisorService->verObservacionTecnico($solicitudId);
        return response()->json($response['data'], $response['status']);
    }


}
