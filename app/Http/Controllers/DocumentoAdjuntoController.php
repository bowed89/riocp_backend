<?php

namespace App\Http\Controllers;

use App\Events\MenuUpdated;
use App\Models\DocumentoAdjunto;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentoAdjuntoController extends Controller
{

    public function storeDocumentoForm1(Request $request)
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
            $anexos_duplicado_1 = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
                ->where('tipo_documento_id', 1)
                ->first();

            if ($anexos_duplicado_1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se adjunto un cronograma de pagos.'
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
                $filePath = $file->store('cronograma-pagos' . '/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
            }

            // Crear documento adjunto con los datos
            $documentoAdjunto = new DocumentoAdjunto();
            $documentoAdjunto->ruta_documento = $filePath;
            $documentoAdjunto->solicitud_id = $solicitud->id;
            $documentoAdjunto->tipo_documento_id = $request->tipo_documento_id;
            $documentoAdjunto->save();

            // Actualizo mi menu pestania 
            $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();

            if ($request->tipo_documento_id == 1) {
                $menu->formulario_1_anexo = true;
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
            $anexos_duplicado_2 = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
                ->where('tipo_documento_id', 2)
                ->first();

            if ($anexos_duplicado_2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se adjunto un desmebolso.'
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
                $filePath = $file->store('desembolsos' . '/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
            }

            // Crear documento adjunto con los datos
            $documentoAdjunto = new DocumentoAdjunto();
            $documentoAdjunto->ruta_documento = $filePath;
            $documentoAdjunto->solicitud_id = $solicitud->id;
            $documentoAdjunto->tipo_documento_id = $request->tipo_documento_id;
            $documentoAdjunto->save();

            // Actualizo mi menu pestania 
            $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();

            if ($request->tipo_documento_id == 2) {
                $menu->formulario_2_anexo = true;
                $menu->registro = false;
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
