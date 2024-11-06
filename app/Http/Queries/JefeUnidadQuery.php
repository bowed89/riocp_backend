<?php

namespace App\Http\Queries;

use App\Models\Seguimientos;
use Illuminate\Support\Facades\DB;

class JefeUnidadQuery
{
    public static function getJefeUnidadList($user)
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
            ->where('ed.tipo', 'SIN DERIVAR')
            ->get();
    }
}
