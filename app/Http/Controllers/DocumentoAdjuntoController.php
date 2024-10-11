<?php

namespace App\Http\Controllers;

use App\Models\DocumentoAdjunto;
use App\Models\Solicitud;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentoAdjuntoController extends Controller
{
    public function storeDocumentoAdjunto(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // obtengo el id de la solicitud incompleta del usuario 
            $solicitud = Solicitud::where('usuario_id', $user->id)
                ->where('estado_requisito_id', 1) // 1 es incompleto
                ->first();

            if (!$solicitud) {
                return response()->json([
                    'status' => false,
                    'message' => 'No se encontró una solicitud del usuario en proceso. Primero debe completar el FORMULARIO 1 SOLICITUD RIOCP.'
                ], 404);
            }

            // Verifico que no exista un tipo_documento_id creado anteriormente con el mismo #

            // verifico que no exista un documento creado anteriormente con la misma solicitud_id 
            $anexos_duplicado_1 = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
                ->where('tipo_documento_id', 1)
                ->first();

            $anexos_duplicado_2 = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
                ->where('tipo_documento_id', 2)
                ->first();

            if ($anexos_duplicado_1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se adjunto un cronograma de pagos.'
                ], 404);
            }

            if ($anexos_duplicado_2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se adjunto un desmebolso.'
                ], 404);
            }

            $formularioRules = [
                'documentos' => 'required|file|mimes:pdf,xlsx,xls|max:10240', // max 10mb
                'tipo_documento_id' => 'required|integer'
            ];

            // Validar los datos del formulario...
            $formularioValidator = Validator::make($request->all(), $formularioRules);

            if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $formularioValidator->errors()->all()
                ], 400);
            }


            // manejo de subida de archivos
            foreach ($request['documentos'] as $documento) {
                $filePath = null;

                if ($documento['documento']) {
                    $file = $documento['documento'];
                    $nombres = $user->nombre . ' ' . $user->apellido;
                    $entidad = $user->entidad->denominacion;
                    $fechaActual = now()->format('Y-m-d');
                    $filePath = $file->store('anexos/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
                }

                // Crear documento adjunto con los datos
                $documentoAdjunto = new DocumentoAdjunto();
                $documentoAdjunto->ruta_documento = $filePath;
                $documentoAdjunto->solicitud_id = $solicitud->id;
                $documentoAdjunto->tipo_documento_id = $documento['tipo_documento_id'];
                $documentoAdjunto->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Anexos agregados correctamente.'
            ], 200);
        }
    }
}
