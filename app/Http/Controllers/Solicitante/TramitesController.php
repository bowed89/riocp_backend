<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TramitesController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        if ($user) {
            $resultados = DB::table('solicitudes AS s')
                ->select(
                    DB::raw("COALESCE(s.nro_hoja_ruta, 'AÚN NO SE ASIGNO') AS nro_hoja_ruta"),
                    DB::raw('s.nro_solicitud'),
                    DB::raw('s.created_at'),
                    DB::raw('UPPER(es.tipo) AS tipo_estado_solicitud'),
                    DB::raw('UPPER(er.tipo) AS tipo_estado_requisito')
                )
                ->join('estados_solicitud AS es', 'es.id', '=', 's.estado_solicitud_id')
                ->join('estados_requisito AS er', 'er.id', '=', 's.estado_requisito_id')
                ->where('s.usuario_id', $user->id)
                ->get();

            if ($resultados->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No se encontraron tramites.',
                ], 404);
            }


            return response()->json([
                'status' => true,
                'message' => 'Listado de tramites.',
                'data' => $resultados,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
