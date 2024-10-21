<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solicitante\DocumentoAdjunto\Documento_Form2_Request;
use App\Http\Requests\Solicitante\DocumentoAdjunto\Documento_Form_Request;
use App\Http\Services\Solicitante\DocumentoAdjuntoService;

class DocumentoAdjuntoController extends Controller
{
    protected $documentoAdjuntoService;

    public function __construct(DocumentoAdjuntoService $documentoAdjuntoService)
    {
        $this->documentoAdjuntoService = $documentoAdjuntoService;
    }

    public function storeDocumentosFormulario1(Documento_Form_Request $request)
    {
        $response = $this->documentoAdjuntoService->storeDocumentosFormulario1($request->validated());

        return response()->json($response, $response['status'] ? 200 : 400);
    }

    public function storeDocumentoForm2(Documento_Form2_Request $request)
    {
        $response = $this->documentoAdjuntoService->storeDocumentoForm2($request->validated());

        return response()->json($response, $response['status'] ? 200 : 400);
    }

    public function storeDocumentoForm3(Documento_Form2_Request $request)
    {
        $response = $this->documentoAdjuntoService->storeDocumentoForm3($request->validated());

        return response()->json($response, $response['status'] ? 200 : 400);
    }
}
