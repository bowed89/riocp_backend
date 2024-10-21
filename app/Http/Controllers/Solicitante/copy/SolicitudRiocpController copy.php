<?php

namespace App\Http\Controllers\Solicitante;

use App\Events\MenuUpdated;
use App\Http\Controllers\Controller;
use App\Models\ContactoSubsanar;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use App\Models\SolicitudRiocp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SolicitudRiocpController extends Controller
{

    public function index()
    {
        $solicitud = SolicitudRiocp::all();

        if ($solicitud->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay solicitudes registrados.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $solicitud,
        ], 200);
    }

    public function storeSolicitudFormularioRiocp(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // pregunto si hay solicitudes con requisitos incompletos
            $solicitudFaltante = Solicitud::where('usuario_id', $user->id)
                ->where('estado_requisito_id', 1) // 1 es incompleto
                ->get();

            if ($solicitudFaltante->isEmpty()) {
                // Reglas de validación para el formulario
                $formularioRules = [
                    'monto_total' => 'required|numeric',
                    'plazo' => 'required|integer|min:1',
                    'interes_anual' => 'required|numeric',
                    'declaracion_jurada' => 'required|string',
                    'comisiones' => 'max:255',
                    'periodo_gracia' => 'required|integer|min:0',
                    'objeto_operacion_credito' => 'required|string|max:255',
                    'firma_digital' => 'required|boolean', //*
                    'ruta_documento' => 'string|max:255', //*
                    'documento' => 'required|file|mimes:pdf|max:10240', //** */ Validar archivo PDF, máximo 10MB
                    'acreedor_id' => 'required|exists:acreedores,id',
                    'moneda_id' => 'required|exists:monedas,id',
                    'entidad_id' => 'required|exists:entidades,id',
                    'identificador_id' => 'required|exists:identificadores_credito,id',
                    'periodo_id' => 'required|exists:periodos,id',
                    // Contactos subsanar
                    'nombre_completo' => 'required|string|max:255',
                    'correo_electronico' => 'required|email|max:255',
                    'cargo' => 'required|string|max:255',
                    'telefono' => 'required|integer'
                ];

                // Validar los datos del formulario
                $formularioValidator = Validator::make($request->all(), $formularioRules);

                if ($formularioValidator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $formularioValidator->errors()->all()
                    ], 400);
                }

                // Validar que firma_digital sea TRUE 
                if (!$request->input('firma_digital')) {
                    return response()->json([
                        'status' => false,
                        'message' => 'El formulario PDF no tiene firma digital.'
                    ], 400);
                }

                // Crear la solicitud
                $solicitud = new Solicitud();
                $solicitud->usuario_id = $user->id; // Asigna el usuario autenticado
                $solicitud->save();

                // Crear Contacto Subsanar
                $contacto = new ContactoSubsanar();
                $contacto->nombre_completo = $request->input('nombre_completo');
                $contacto->correo_electronico = $request->input('correo_electronico');
                $contacto->cargo = $request->input('cargo');
                $contacto->telefono = $request->input('telefono');
                $contacto->save();

                // Manejo de la subida del archivo
                $filePath = null;
                if ($request->hasFile('documento')) {
                    $file = $request->file('documento');
                    $nombres = $user->nombre . ' ' . $user->apellido;
                    $entidad = $user->entidad->denominacion;
                    $fechaActual = now()->format('Y-m-d');
                    $filePath = $file->store('formularios-riocp/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
                }


                // Crear el Solicitud RIOCP
                $solicitudRiocp = new SolicitudRiocp($request->except('nombre_completo', 'correo_electronico', 'cargo', 'telefono', 'documento'));
                $solicitudRiocp->ruta_documento = $filePath;
                $solicitudRiocp->solicitud_id = $solicitud->id;
                $solicitudRiocp->contacto_id = $contacto->id;
                $solicitudRiocp->save();

                // Actualizo mi menu pestania para habilitar formulario_2
                $menu = MenuPestaniasSolicitante::where('solicitud_id', null)->first();
                $menu->solicitud_id = $solicitud->id;
                $menu->formulario_1 = true;
                $menu->formulario_1_anexo = false;
                $menu->formulario_2 = false;
                $menu->save();
                $menu->refresh(); // devuelve todos los campos no solo created_at y updated_at
                // Iterar y ajustar el estado `disabled` basado en la clave del array
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
                    'message' => 'Formulario registrado correctamente.',
                    'data' => $solicitudRiocp
                ], 200);
            } 

            return response()->json([
                'status' => false,
                'message' => 'Tiene una solicitud sin completar. Complete o cancele su solicitud anterior.',
                'data' => $solicitudFaltante
            ], 400);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
