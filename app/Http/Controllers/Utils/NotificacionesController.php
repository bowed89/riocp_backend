<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Http\Services\Utils\NotificacionesService;

class NotificacionesController extends Controller
{
    protected $notificacionesService;

    public function __construct(NotificacionesService $notificacionesService)
    {
        $this->notificacionesService = $notificacionesService;
    }

    public function index()
    {
        $response = $this->notificacionesService->NotificacionJefeUnidad();
        //return response()->json($response['data'], $response['status']);
        return response()->json($response, $response['code']);

    }
}
