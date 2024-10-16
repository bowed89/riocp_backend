<?php

namespace App\Http\Controllers\Solicitante;

use App\Events\MenuUpdated;
use App\Http\Controllers\Controller;
use App\Models\DocumentoAdjunto;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentoAdjuntoController extends Controller
{

    public function storeDocumentosFormulario1(Request $request)
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

            // verifico que no exista un documento creado anteriormente con la misma solicitud_id 
            $anexos_duplicados = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
                ->whereIn('tipo_documento_id', [1, 2])
                ->get();

            if ($anexos_duplicados->isNotEmpty()) {
                // procesar el caso de duplicado según el tipo de documento
                foreach ($anexos_duplicados as $anexo) {
                    if ($anexo->tipo_documento_id == 1) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Ya se adjunto un cronograma de pagos.'
                        ], 404);
                    } elseif ($anexo->tipo_documento_id == 2) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Ya se adjunto un cronograma de desembolsos.'
                        ], 404);
                    }
                }
            }

            $formularioRules = [
                'documento_cronograma' => 'required|file|mimes:pdf,xlsx,xls|max:10240',
                'documento_desembolso' => 'required|file|mimes:pdf,xlsx,xls|max:10240',
                'tipo_documento_id_cronograma' => 'required|integer',
                'tipo_documento_id_desembolso' => 'required|integer',
            ];

            // Validar los datos del formulario...
            $formularioValidator = Validator::make($request->all(), $formularioRules);

            if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $formularioValidator->errors()->all()
                ], 400);
            }

            // Manejo de la subida de cronograma de pagos
            if ($request->hasFile('documento_cronograma')) {
                $fileCronograma = $request->file('documento_cronograma');
                $nombres = $user->nombre . ' ' .  $user->apellido;
                $entidad = $user->entidad->denominacion;
                $fechaActual = now()->format('Y-m-d');
                $filePathCronograma = $fileCronograma->store('cronograma-pagos/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');

                $documentoAdjuntoCronograma = new DocumentoAdjunto();
                $documentoAdjuntoCronograma->ruta_documento = $filePathCronograma;
                $documentoAdjuntoCronograma->solicitud_id = $solicitud->id;
                $documentoAdjuntoCronograma->tipo_documento_id = $request->tipo_documento_id_cronograma;
                $documentoAdjuntoCronograma->save();
            }
            // Manejo de la subida de desembolso de pagos
            if ($request->hasFile('documento_desembolso')) {
                $fileDesembolso = $request->file('documento_desembolso');
                $filePathDesembolso = $fileDesembolso->store('desembolsos/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');

                $documentoAdjuntoDesembolso = new DocumentoAdjunto();
                $documentoAdjuntoDesembolso->ruta_documento = $filePathDesembolso;
                $documentoAdjuntoDesembolso->solicitud_id = $solicitud->id;
                $documentoAdjuntoDesembolso->tipo_documento_id = $request->tipo_documento_id_desembolso;
                $documentoAdjuntoDesembolso->save();
            }

            // Actualizo mi menu pestania 
            $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
            $menu->formulario_1_anexo = true;
            $menu->save();
            $menu->refresh();

            $items = config('menu_pestanias');
            foreach ($items as &$item) {
                $key = $item['disabled'];
                if (isset($menu->$key)) {
                    $item['disabled'] = $menu->$key;
                }
            }

            event(new MenuUpdated($items));
            return response()->json([
                'status' => true,
                'message' => 'Anexo agregado correctamente.'
            ], 200);
        }
    }

    public function storeDocumentoForm2(Request $request)
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

            // verifico que no exista un documento creado anteriormente con la misma solicitud_id
            $anexos_duplicado_4 = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
                ->where('tipo_documento_id', 4)
                ->first();

            if ($anexos_duplicado_4) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se adjunto un certificado RIOCP no vigente.'
                ], 404);
            }

            $formularioRules = [
                'documento' => 'required|file|mimes:pdf,xlsx,xls|max:10240', // max 10mb
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

            // Manejo de la subida del archivo
            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $nombres = $user->nombre . ' ' .  $user->apellido;
                $entidad = $user->entidad->denominacion;
                $fechaActual = now()->format('Y-m-d');
                $filePath = null;
                // Guardar el archivo en el almacenamiento local y obtener la ruta
                $filePath = $file->store('certificado-riocp-no-vigente' . '/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
            }
            // Crear documento adjunto con los datos
            $documentoAdjunto = new DocumentoAdjunto();
            $documentoAdjunto->ruta_documento = $filePath;
            $documentoAdjunto->solicitud_id = $solicitud->id;
            $documentoAdjunto->tipo_documento_id = $request->tipo_documento_id;
            $documentoAdjunto->save();
        }
    }

    public function storeDocumentoForm3(Request $request)
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

            // verifico que no exista un documento creado anteriormente con la misma solicitud_id
            $anexos_duplicado_3 = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
                ->where('tipo_documento_id', 3)
                ->first();

            if ($anexos_duplicado_3) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se adjunto una información financiera.'
                ], 404);
            }

            $formularioRules = [
                'documento' => 'required|file|mimes:pdf,xlsx,xls|max:10240', // max 10mb
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

            // Manejo de la subida del archivo
            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $nombres = $user->nombre . ' ' .  $user->apellido;
                $entidad = $user->entidad->denominacion;
                $fechaActual = now()->format('Y-m-d');
                $filePath = null;
                // Guardar el archivo en el almacenamiento local y obtener la ruta
                $filePath = $file->store('informacion-financiera' . '/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
            }
            // Crear documento adjunto con los datos
            $documentoAdjunto = new DocumentoAdjunto();
            $documentoAdjunto->ruta_documento = $filePath;
            $documentoAdjunto->solicitud_id = $solicitud->id;
            $documentoAdjunto->tipo_documento_id = $request->tipo_documento_id;
            $documentoAdjunto->save();

            // Actualizo mi menu pestania 
            $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
            if ($request->tipo_documento_id == 3) {
                $menu->sigep_anexo = true;
                $menu->save();
                $menu->refresh();
            }
            $items = config('menu_pestanias');
            foreach ($items as &$item) {
                $key = $item['disabled'];
                if (isset($menu->$key)) {
                    $item['disabled'] = $menu->$key;
                }
            }
            // evento con los datos del menu
            event(new MenuUpdated($items));

            return response()->json([
                'status' => true,
                'message' => 'Anexo agregado correctamente.'
            ], 200);
        }
    }
}
