<?php

namespace App\Http\Services\Revisor;

use App\Events\Notificaciones;
use App\Http\Queries\JefeUnidadQuery;
use App\Models\Observacion;
use App\Models\Seguimientos;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeguimientoRevisorService
{
    public function obtenerSeguimientos()
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => 403,
                'data' => [
                    'status' => false,
                    'message' => 'Usuario no autorizado o sin rol asignado.'
                ]
            ];
        }

        $resultados = Seguimientos::select(
            'so.nro_solicitud',
            's.id AS id_seguimiento',
            DB::raw('COALESCE(s.created_at::text, \'SIN DATOS\') AS fecha_recepcion'),
            DB::raw('COALESCE(s.fecha_derivacion::text, \'SIN DATOS\') AS fecha_derivacion'),
            DB::raw('COALESCE(s.observacion, \'SIN DATOS\') AS observacion'),
            DB::raw('so.id as solicitud_id'),
            DB::raw('u_origen.nombre AS nombre_origen'),
            DB::raw('u_origen.apellido AS apellido_origen'),
            DB::raw('u_destino.nombre AS nombre_destino'),
            DB::raw('u_destino.apellido AS apellido_destino'),
            DB::raw('COALESCE(so.nro_hoja_ruta, \'SIN DATOS\') AS nro_hoja_ruta'),
            DB::raw('COALESCE(r_origen.rol, \'SIN DATOS\') AS rol_origen'),
            DB::raw('COALESCE(r_destino.rol, \'SIN DATOS\') AS rol_destino'),
            DB::raw('COALESCE(ed.tipo, \'SIN DATOS\') AS estado')
        )
            ->from('seguimientos AS s')
            ->join('solicitudes AS so', 's.solicitud_id', '=', 'so.id')
            ->join('estados_requisito AS er', 'er.id', '=', 'so.estado_requisito_id')
            ->join('estados_derivado AS ed', 'ed.id', '=', 's.estado_derivado_id')
            ->join('usuarios AS u_origen', 'u_origen.id', '=', 's.usuario_origen_id')
            ->join('roles AS r_origen', 'r_origen.id', '=', 'u_origen.rol_id')
            ->join('usuarios AS u_destino', 'u_destino.id', '=', 's.usuario_destino_id')
            ->join('roles AS r_destino', 'r_destino.id', '=', 'u_destino.rol_id')
            ->where('u_destino.rol_id', $user->rol_id)
            ->where('u_destino.id', $user->id)
            ->get();

        if ($resultados->isEmpty()) {
            return [
                'status' => 200,
                'data' => [
                    'status' => false,
                    'message' => 'No se encontraron seguimientos.'
                ]
            ];
        }

        return [
            'status' => 200,
            'data' => [
                'status' => true,
                'message' => 'Listado de seguimientos.',
                'data' => $resultados,
            ]
        ];
    }

    public function asignarRevisora($data)
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

        $solicitud = Solicitud::where('id', $data['solicitud_id'])->first();

        if (!$solicitud) {
            return [
                'status' => 400,
                'data' => [
                    'status' => false,
                    'message' => 'No existe una solicitud.'
                ]
            ];
        }
        // actualizar seguimiento de jefe de unidad a revisor
        $seguimientoOrigen = Seguimientos::where('id', $data['id_seguimiento'])->first();

        if (!$seguimientoOrigen) {
            return [
                'status' => 400,
                'data' => [
                    'status' => false,
                    'message' => 'No existe un seguimiento origen.'
                ]
            ];
        }

        $seguimientoOrigen->estado_derivado_id = 2;
        $seguimientoOrigen->observacion = $data['observacion'];
        $seguimientoOrigen->fecha_derivacion = Carbon::now();
        $seguimientoOrigen->save();

        // Agregar seguimiento para la próxima unidad
        $seguimientoProximaUnidad = Seguimientos::where('solicitud_id', $data['solicitud_id'])
            ->where('usuario_origen_id', $user->id)
            ->where('usuario_destino_id', $data['usuario_destino_id'])
            ->first();

        if ($seguimientoProximaUnidad) {
            return [
                'status' => 400,
                'data' => [
                    'status' => false,
                    'message' => 'Ya existe un seguimiento agregado a la próxima unidad.'
                ]
            ];
        }

        $seguimiento = new Seguimientos();
        $seguimiento->solicitud_id = $data['solicitud_id'];
        $seguimiento->usuario_origen_id = $user->id;
        $seguimiento->usuario_destino_id = $data['usuario_destino_id'];
        $seguimiento->estado_derivado_id = 1;
        $seguimiento->save();

        // Agregar observaciones
        foreach ($data['observaciones'] as $observacion) {
            $newObservacion = new Observacion();
            $newObservacion->cumple = $observacion['cumple'];
            $newObservacion->observacion = $observacion['observacion'];
            $newObservacion->tipo_observacion_id = $observacion['tipo_observacion_id'];
            $newObservacion->solicitud_id = $solicitud->id;
            $newObservacion->usuario_id = $user->id;
            $newObservacion->rol_id = $user->rol_id;
            $newObservacion->save();
        }


        // Event para notificaciones de nuevos tramites
        $this->emitNotificacion($user);

        return [
            'status' => 200,
            'data' => [
                'status' => true,
                'message' => 'Seguimiento registrado.',
                'data' => $seguimiento
            ]
        ];
    }

    private function emitNotificacion($user)
    {
        $resultados = JefeUnidadQuery::getJefeUnidadList($user);
        $count = 0;
        foreach ($resultados as $res) {
            if ($res['estado'] == 'SIN DERIVAR') {
                $count += 1;
            }
        }
        event(new Notificaciones($count));
    }
}
