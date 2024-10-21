<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use App\Models\Seguimientos;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SeguimientoOperadorController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        if ($user) {
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
                return response()->json([
                    'status' => false,
                    'message' => 'No se encontraron seguimientos.'
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'Listado de seguimientos.',
                'data' => $resultados,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }

    public function asignardeOperadoraRevisor(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $rules = [
                'id_seguimiento' => 'required|integer',
                'observacion' => 'required|string',
                'solicitud_id' => 'required|integer',
                'usuario_destino_id' => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }
            // actualizar solicitud 
            $solicitud = Solicitud::where('id', $request->solicitud_id)->first();

            if (!$solicitud) {
                return response()->json([
                    'status' => false,
                    'message' => 'No existe una solicitud.'
                ], 400);
            }
            // actualizar seguimiento 
            $seguimientoOrigen = Seguimientos::where('id', $request->id_seguimiento)->first();

            if (!$solicitud) {
                return response()->json([
                    'status' => false,
                    'message' => 'No existe un seguimiento origen.'
                ], 400);
            }

            $seguimientoOrigen->estado_derivado_id = 2;
            $seguimientoOrigen->observacion = $request->observacion;
            $seguimientoOrigen->fecha_derivacion = Carbon::now();
            $seguimientoOrigen->save();

            // agregar seguimiento para la proxima unidad
            $seguimiento = new Seguimientos();
            $seguimiento->solicitud_id = $request->solicitud_id;
            $seguimiento->usuario_origen_id = $user->id;
            $seguimiento->usuario_destino_id = $request->usuario_destino_id;
            $seguimiento->estado_derivado_id = 1;
            $seguimiento->save();

            return response()->json([
                'status' => true,
                'message' => 'Seguimiento registrado.',
                'data' => $seguimiento
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
