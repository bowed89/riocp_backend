<?php

namespace App\Http\Controllers;

use App\Models\ContactoSubsanar;
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

    /*   public function storeSolicitudFormularioRiocp(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Crear la solicitud
            $solicitud = new Solicitud();
            $solicitud->usuario_id = $user->id; // Asigna el usuario autenticado
            $solicitud->save();

            $formularioRules = [
                'monto_total' => 'required|string|max:255',
                'plazo' => 'required|integer|min:1',
                'interes_anual' => 'required|integer|min:1',
                'comisiones' => 'required|string|max:255',
                'periodo_gracia' => 'required|integer|min:0',
                'objeto_operacion_credito' => 'required|string|max:255',
                'firma_digital' => 'boolean',
                'solicitud_id' => 'required|exists:solicitudes,id',
                'acreedor_id' => 'required|exists:acreedores,id',
                'moneda_id' => 'required|exists:monedas,id',
                'entidad_id' => 'required|exists:entidades,id',
                'identificador_id' => 'required|exists:identificadores_credito,id',
                'periodo_id' => 'required|exists:periodos,id',
                'contacto_id' => 'required|exists:contactos_subsanar,id',
                //contactos subsanar
                'nombre_completo' => 'required|string|max:255',
                'correo_electronico' => 'required|email|max:255',
                'cargo' => 'required|string'
            ];

            $formularioValidator = Validator::make($request->all(), $formularioRules);

            if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $formularioValidator->errors()->all()
                ], 400);
            }
            // Crear Contacto Subsanar
            $contactoRules = 
            $contactoValidator = Validator::make($request->except('nombre_completo', 'correo_electronico', 'cargo'), $formularioRules);



            // Crear el Solicitud RIOCP
            $solicitudRiocp = new SolicitudRiocp($request->input());
            $solicitudRiocp->solicitud_id = $solicitud->id;
            $solicitudRiocp->save();

            return response()->json([
                'status' => true,
                'message' => 'Formulario registrado correctamente.',
                'data' => $solicitudRiocp
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    } */

    public function storeSolicitudFormularioRiocp(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // pregunto si hay solicitudes con requisitos incompletos
            $solicitudFaltante = Solicitud::where('usuario_id', $user->id)
                ->where('estado_requisito_id', 1) // 1 es incompleto
                ->get();

            if ($solicitudFaltante->isEmpty()) {
                // Crear la solicitud
                $solicitud = new Solicitud();
                $solicitud->usuario_id = $user->id; // Asigna el usuario autenticado
                $solicitud->save();

                // Reglas de validación para el formulario
                $formularioRules = [
                    'monto_total' => 'required|string|max:255',
                    'plazo' => 'required|integer|min:1',
                    'interes_anual' => 'required|integer|min:1',
                    'comisiones' => 'required|string|max:255',
                    'periodo_gracia' => 'required|integer|min:0',
                    'objeto_operacion_credito' => 'required|string|max:255',
                    'firma_digital' => 'boolean',
                    'acreedor_id' => 'required|exists:acreedores,id',
                    'moneda_id' => 'required|exists:monedas,id',
                    'entidad_id' => 'required|exists:entidades,id',
                    'identificador_id' => 'required|exists:identificadores_credito,id',
                    'periodo_id' => 'required|exists:periodos,id',
                    // Contactos subsanar
                    'nombre_completo' => 'required|string|max:255',
                    'correo_electronico' => 'required|email|max:255',
                    'cargo' => 'required|string|max:255',
                    'estado' => 'required|boolean'
                ];

                // Validar los datos del formulario
                $formularioValidator = Validator::make($request->all(), $formularioRules);

                if ($formularioValidator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $formularioValidator->errors()->all()
                    ], 400);
                }

                // Crear Contacto Subsanar
                $contacto = new ContactoSubsanar();
                $contacto->nombre_completo = $request->input('nombre_completo');
                $contacto->correo_electronico = $request->input('correo_electronico');
                $contacto->cargo = $request->input('cargo');
                $contacto->estado = $request->input('estado');
                $contacto->save();

                // Crear el Solicitud RIOCP
                $solicitudRiocp = new SolicitudRiocp($request->except('nombre_completo', 'correo_electronico', 'cargo', 'estado'));
                $solicitudRiocp->solicitud_id = $solicitud->id;
                $solicitudRiocp->contacto_id = $contacto->id;
                $solicitudRiocp->save();

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
