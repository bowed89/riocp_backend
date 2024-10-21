<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\ObservacionTecnico;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ObservacionTecnicoController extends Controller
{
    public function store(Request $request)
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

            // Verifico que no existan registros creados anteriormente
            $observacionDuplicada = ObservacionTecnico::where('solicitud_id', $solicitud->id)->first();

            if ($observacionDuplicada) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se registro observaciones con una solicitud pendiente.'
                ], 404);
            }

            $formularioRules = [
                // Reglas para cronograma_desembolsos
                'observaciones' => 'required|array',
                'observaciones.*.cumple' => 'required|boolean',
                'observaciones.*.tipo_observacion_id' => 'required|integer',
                'observaciones.*.solicitud_id' => 'required|integer'
            ];

            $formularioValidator = Validator::make($request->all(), $formularioRules);

            if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $formularioValidator->errors()->all()
                ], 400);
            }

            foreach ($request['observaciones'] as $observacion) {
                $newObservacion = new ObservacionTecnico();
                $newObservacion->cumple = $observacion['cumple'];
                $newObservacion->tipo_observacion_id = $observacion['tipo_observacion_id'];
                $newObservacion->solicitud_id = $observacion['solicitud_id'];
                $newObservacion->usuario_id = $user->id;
                $newObservacion->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Se registraron las observaciones correctamente.',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
