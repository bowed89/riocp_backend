<?php

namespace App\Http\Services\JefeUnidad;

use App\Events\Notificaciones;
use App\Http\Queries\JefeUnidadQuery;
use App\Models\Seguimientos;
use App\Models\Solicitud;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeguimientoJefeUnidadService
{
    public function listarSeguimientos()
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.',
                'data' => $user
            ];
        }

        $resultados = $this->querySeguimiento($user);

        if ($resultados->isEmpty()) {
            return [
                'status' => 200,
                'message' => 'No se encontraron seguimientos.'
            ];
        }

        return [
            'status' => true,
            'message' => 'Listado de seguimientos.',
            'data' => $resultados
        ];
    }

    public function asignarTecnicoRevisor($data)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        $solicitud = Solicitud::where('id', $data['solicitud_id'])->first();

        if (!$solicitud) {
            return [
                'status' => false,
                'message' => 'No existe una solicitud.'
            ];
        }

        // Actualizar seguimiento de origen
        $seguimientoOrigen = Seguimientos::where('id', $data['id_seguimiento'])->first();

        if (!$seguimientoOrigen) {
            return [
                'status' => false,
                'message' => 'No existe un seguimiento inicial de origen.'
            ];
        }

        $seguimientoOrigen->estado_derivado_id = 2;
        $seguimientoOrigen->observacion = $data['observacion'];
        $seguimientoOrigen->fecha_derivacion = Carbon::now();
        $seguimientoOrigen->save();

        // Verificar el tipo de rol del usuario por el seguimiento de usuario_origen_id 
        $usuarioOrigen = Usuario::where('id', $seguimientoOrigen->usuario_origen_id)->first();

        // entidad solicitante
        if ($usuarioOrigen->rol_id == 1) {
            // actualizar solicitud 
            $solicitud->nro_hoja_ruta = $data['nro_hoja_ruta'];
            $solicitud->estado_requisito_id = 2;
            $solicitud->save();
        }
        // agregar seguimiento para la proxima unidad:: tecnico o DGAFT
        $seguimientoProximaUnidad = Seguimientos::where('solicitud_id', $solicitud->id)
            ->where('usuario_origen_id', $user->id)
            ->where('usuario_destino_id', $data['usuario_destino_id'])
            ->first();

        if ($seguimientoProximaUnidad) {
            return [
                'status' => false,
                'message' => 'Ya existe un seguimiento agregado a la próxima unidad.'
            ];
        }

        $seguimiento = new Seguimientos();
        $seguimiento->solicitud_id = $solicitud->id;
        $seguimiento->usuario_origen_id = $user->id;
        $seguimiento->usuario_destino_id = $data['usuario_destino_id'];
        $seguimiento->estado_derivado_id = 1;
        $seguimiento->save();

        // Event para notificaciones de nuevos tramites
        $this->emitNotificacion($user);

        return [
            'status' => true,
            'message' => 'Derivación registrada correctamente.',
        ];
    }

    public function countDerivado()
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.',
                'data' => $user
            ];
        }

        $resultados = $this->querySeguimiento($user);

        if ($resultados->isEmpty()) {
            return [
                'status' => true,
                'message' => 'No se encontraron seguimientos.',
                'data' => null
            ];
        }
        $countDerivado = 0;
        $countNoDerivado = 0;

        foreach ($resultados as $resultado) {
            if ($resultado->estado == 'DERIVADO') {
                $countDerivado += 1;
            } else if ($resultado->estado == 'SIN DERIVAR') {
                $countNoDerivado += 1;
            }
        }

        return [
            'status' => true,
            'message' => 'Contador Derivados.',
            'data' => [
                "derivados" => $countDerivado,
                "no_derivados" => $countNoDerivado
            ]
        ];
    }

    private function querySeguimiento($user)
    {
        return Seguimientos::select(
            'so.nro_solicitud',
            's.id AS id_seguimiento',
            DB::raw('COALESCE(s.created_at::text, \'SIN DATOS\') AS fecha_recepcion'),
            DB::raw('COALESCE(s.fecha_derivacion::text, \'SIN DATOS\') AS fecha_derivacion'),
            DB::raw('COALESCE(s.observacion, \'SIN DATOS\') AS observacion'),

            DB::raw("COALESCE(UPPER(e_origen.denominacion), '') AS denominacion"),

            DB::raw('so.id as solicitud_id'),
            DB::raw('r_origen.id AS id_rol_origen'),
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
            ->leftJoin('entidades AS e_origen', 'e_origen.id', '=', 'u_origen.entidad_id')
            ->join('roles AS r_destino', 'r_destino.id', '=', 'u_destino.rol_id')
            ->where('u_destino.rol_id', $user->rol_id)
            ->where('u_destino.id', $user->id)
            ->get();
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
