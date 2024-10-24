<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Http\Services\Utils\AbrirDocumentoService;

class AbrirDocumentoController extends Controller
{
    protected $abrirDocumentoService;

    public function __construct(AbrirDocumentoService $abrirDocumentoService)
    {
        $this->abrirDocumentoService = $abrirDocumentoService;
    }

    public function abrirAllDocumentos($id, $idTipo)
    {
        return $this->abrirDocumentoService->abrirDocumento($id, $idTipo);
        // return response()->json($response, $response['status'] ? 200 : 403);
    }

    public function abrirDocumentoRiocp($id)
    {
        return $this->abrirDocumentoService->abrirDocumentoRiocp($id);
        // return response()->json($response, $response['status'] ? 200 : 403);
    }

    public function abrirFormularioCorrespondencia($id)
    {
        return $this->abrirDocumentoService->abrirFormularioCorrespondencia($id);
        //return response()->json($response, $response['status'] ? 200 : 403);
    }
}
