<?php

namespace App\Http\Services\Utils;

use App\Models\DocumentoAdjunto;
use App\Models\FormularioCorrespondencia;
use App\Models\SolicitudRiocp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbrirDocumentoService
{
    public function abrirDocumento($id, $idTipo)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        $documento = DocumentoAdjunto::where('solicitud_id', $id)
            ->where('tipo_documento_id', $idTipo)
            ->first();

        if (!$documento) {
            return [
                'status' => false,
                'message' => 'Documento no encontrado.',
                'code' => 400,
            ];
        }

        $rutaArchivo = 'public/' . $documento->ruta_documento;

        if (!Storage::exists($rutaArchivo)) {
            return [
                'status' => false,
                'message' => 'Archivo no encontrado.',
                'code' => 400,
            ];
        }

        // return Storage::download($rutaArchivo);

        $mimeType = Storage::mimeType($rutaArchivo);
        $nombreArchivo = basename($documento->ruta_documento);

        return Storage::download($rutaArchivo, $nombreArchivo, ['Content-Type' => $mimeType]);
    }

    public function abrirDocumentoRiocp($id)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        $documento = SolicitudRiocp::where('solicitud_id', $id)->first();

        if (!$documento) {
            return [
                'status' => false,
                'message' => 'Solicitud no encontrada.',
                'code' => 400,
            ];
        }

        $rutaArchivo = 'public/' . $documento->ruta_documento;

        if (!Storage::exists($rutaArchivo)) {
            return [
                'status' => false,
                'message' => 'Archivo no encontrado.',
                'code' => 400,
            ];
        }

        return Storage::download($rutaArchivo);
    }

    public function abrirFormularioCorrespondencia($id)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        $documento = FormularioCorrespondencia::where('solicitud_id', $id)->first();
        if (!$documento) {
            return [
                'status' => false,
                'message' => 'Solicitud no encontrada.',
                'code' => 400,
            ];
        }
        
        $rutaArchivo = 'public/' . $documento->ruta_documento;

        if (!Storage::exists($rutaArchivo)) {
            return [
                'status' => false,
                'message' => 'Archivo no encontrado.',
                'code' => 400,
            ];
        }

        return Storage::download($rutaArchivo);
    }
}
