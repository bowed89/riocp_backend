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
    }

    public function abrirDocumentoRiocp($id)
    {
        return $this->abrirDocumentoService->abrirDocumentoRiocp($id);
    }
}
