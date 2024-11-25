<?php

namespace App\Http\Services\Operador;

use App\Models\Seguimientos;
use App\Models\Solicitud;
use App\Models\SolicitudRiocp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificadoRiocpService
{
    public function obtenerSolicitudCertificado($idSolicitud)
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        $resultados = SolicitudRiocp::select(
            'ic.id AS identificador_id',
            'e.entidad_id AS codigo',
            DB::raw('UPPER(e.denominacion) AS entidad'),
            DB::raw('UPPER(s.objeto_operacion_credito) AS objeto_operacion_credito'),
            's.monto_total',
            's.interes_anual',
            DB::raw('UPPER(s.comision_concepto) AS comision_concepto'),
            's.comision_tasa',
            's.plazo',
            's.periodo_gracia',
            DB::raw('UPPER(ac.nombre) AS acreedor'),
            DB::raw('UPPER(mn.sigla) AS moneda')
        )
            ->from('solicitudes_riocp AS s')
            ->join('identificadores_credito AS ic', 'ic.id', '=', 's.identificador_id')
            ->join('entidades AS e', 'e.id', '=', 's.entidad_id')
            ->join('acreedores AS ac', 'ac.id', '=', 's.acreedor_id')
            ->join('monedas AS mn', 'mn.id', '=', 's.moneda_id')
            ->where('s.id', $idSolicitud)
            ->get();


        if ($resultados->isEmpty()) {
            return [
                'status' => false,
                'message' => 'No se encontraron solicitudes.'
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
