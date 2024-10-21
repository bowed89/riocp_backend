<?php

namespace App\Http\Services\Operador;

use App\Models\ObservacionTecnico;
use App\Models\Solicitud;
use App\Models\TipoObservacionesTecnico;
use Illuminate\Support\Facades\Auth;

class ObservacionTecnicoService
{
    public function guardarObservaciones($request)
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        // Obtengo el id de la solicitud incompleta del usuario
        $solicitud = Solicitud::where('usuario_id', $user->id)
            ->where('estado_requisito_id', 1) // 1 es incompleto
            ->first();

        if (!$solicitud) {
            return [
                'status' => 404,
                'data' => [
                    'status' => false,
                    'message' => 'No se encontró una solicitud del usuario en proceso. Primero debe completar el FORMULARIO 1 SOLICITUD RIOCP.'
                ]
            ];
        }

        // Verifico que no existan registros creados anteriormente
        $observacionDuplicada = ObservacionTecnico::where('solicitud_id', $solicitud->id)->first();

        if ($observacionDuplicada) {
            return [
                'status' => 404,
                'data' => [
                    'status' => false,
                    'message' => 'Ya se registro observaciones con una solicitud pendiente.'
                ]
            ];
        }

        foreach ($request['observaciones'] as $observacion) {
            $newObservacion = new ObservacionTecnico();
            $newObservacion->cumple = $observacion['cumple'];
            $newObservacion->observacion = $observacion['observacion'];
            $newObservacion->tipo_observacion_id = $observacion['tipo_observacion_id'];
            $newObservacion->solicitud_id = $observacion['solicitud_id'];
            $newObservacion->usuario_id = $user->id;
            $newObservacion->save();
        }

        return [
            'status' => 200,
            'data' => [
                'status' => true,
                'message' => 'Se registraron las observaciones correctamente.'
            ]
        ];
    }

    public function verTipoObservaciones()
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'status' => 401,
                'data' => [
                    'status' => false,
                    'message' => 'Usuario no autorizado o sin rol asignado.'
                ]
            ];
        }

        $tipoObservaciones = TipoObservacionesTecnico::orderBy('id', 'asc')->get();

        if ($tipoObservaciones->isEmpty()) {
            return [
                'status' => 404,
                'data' => [
                    'status' => false,
                    'message' => 'No se encontraron tipo de observaciones.'
                ]
            ];
        }

        return [
            'status' => 200,
            'data' => [
                'status' => true,
                'message' => 'Listado de seguimientos.',
                'data' => $tipoObservaciones,
            ]
        ];
    }
}
