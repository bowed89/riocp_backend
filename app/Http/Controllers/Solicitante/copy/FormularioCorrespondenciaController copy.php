<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solicitante\FormularioCorrespondenciaRequest;
use App\Http\Services\Solicitante\FormularioCorrespondenciaService;
use Illuminate\Support\Facades\Log;

class FormularioCorrespondenciaController extends Controller
{
    protected $formularioCorrespondenciaService;

    public function __construct(FormularioCorrespondenciaService $formularioCorrespondenciaService)
    {
        $this->formularioCorrespondenciaService = $formularioCorrespondenciaService;
    }

    public function index()
    {
        $result = $this->formularioCorrespondenciaService->getAllFormularios();
        return response()->json($result, $result['status'] ? 200 : 404);
    }

    public function store(FormularioCorrespondenciaRequest $request)
    {
        $result = $this->formularioCorrespondenciaService->createFormulario($request->validated());
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function storeSolicitudFormulario(FormularioCorrespondenciaRequest $request)
    {
        $validatedData = $request->validated();
        Log::info('Datos validados:', $validatedData); 

        $result = $this->formularioCorrespondenciaService->createSolicitudFormulario($request->validated());
        return response()->json($result, $result['status'] ? 200 : 404);
    }

    public function show($id)
    {
        $result = $this->formularioCorrespondenciaService->getFormularioById($id);
        return response()->json($result, $result['status'] ? 200 : 404);
    }

    public function update(FormularioCorrespondenciaRequest $request, $id)
    {
        $result = $this->formularioCorrespondenciaService->updateFormulario($id, $request->validated());
        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function deleteFormulario($id)
    {
        $result = $this->formularioCorrespondenciaService->deactivateFormulario($id);
        return response()->json($result, $result['status'] ? 200 : 404);
    }
}

