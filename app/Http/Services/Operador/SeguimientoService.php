<?php

namespace App\Http\Services\Operador;

use App\Models\Seguimientos;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeguimientoService
{
    public function listarSeguimientos()
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
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

            DB::raw('so.id as solicitud_id'),

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
                'status' => false,
                'message' => 'No se encontraron seguimientos.'
            ];
        }

        return [
            'status' => true,
            'message' => 'Listado de seguimientos.',
            'data' => $resultados,
        ];
    }

    public function asignarSeguimiento($data)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }
        // actualizar solicitud 
        $solicitud = Solicitud::where('id', $data->solicitud_id)->first();

        if (!$solicitud) {
            return [
                'status' => false,
                'message' => 'No existe una solicitud.'
            ];
        }
        // actualizar seguimiento 
        $seguimientoOrigen = Seguimientos::where('id', $data->id_seguimiento)->first();

        if (!$seguimientoOrigen) {
            return [
                'status' => false,
                'message' => 'No existe un seguimiento origen.'
            ];
        }

        $seguimientoOrigen->estado_derivado_id = 2;
        $seguimientoOrigen->observacion = $data['observacion'];
        $seguimientoOrigen->fecha_derivacion = Carbon::now();
        $seguimientoOrigen->save();

        // agregar seguimiento para la proxima unidad
        $seguimiento = new Seguimientos();
        $seguimiento->solicitud_id = $data['solicitud_id'];
        $seguimiento->usuario_origen_id = $user->id;
        $seguimiento->usuario_destino_id = $data['usuario_destino_id'];
        $seguimiento->estado_derivado_id = 1;
        $seguimiento->save();

        return [
            'status' => true,
            'message' => 'Seguimiento registrado.',
            'data' => $seguimiento,
            'code' => 200
        ];
    }
}
