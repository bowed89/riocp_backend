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

        $codigo_entidad =$resultados[0]->codigo;

        $resultados[0]->servicio_deuda = $this->obtenerServicioDeuda($codigo_entidad);

        return [
            'status' => true,
            'message' => 'Listado de seguimientos.',
            'data' => $resultados,
        ];
    }

    //SERVICIO DE LA DEUDA(LÍMITE 20%)
    public function obtenerServicioDeuda($codigo_entidad)
    {
        $anioActual = Carbon::now()->year;

        $sumCapInteres = DB::table('fndr_excel')
            ->where('codigo_prsupuestario', $codigo_entidad)
            ->where('fecha_de_cuota', 'like', '%' . $anioActual . '%')
            ->selectRaw('SUM(capital::DECIMAL) + SUM(capital_diferido::DECIMAL) + 
                        SUM(interes::DECIMAL) + SUM(interes_diferido::DECIMAL) AS sum_cap_interes')
            ->first();

        // Subconsulta para el cálculo de promedio_icr_eta
        $promedioIcrEta = DB::table('icr_eta_rubro_total_excel')
            ->where('entidad', $codigo_entidad)
            ->where('nombre_total', 'ICR')
            ->selectRaw('ROUND(AVG(monto::DECIMAL), 2) AS promedio_icr_eta')
            ->first();

        if ($sumCapInteres && $promedioIcrEta && $promedioIcrEta->promedio_icr_eta != 0) {
            $resultadoFinal = round(($sumCapInteres->sum_cap_interes / $promedioIcrEta->promedio_icr_eta) * 100, 1);
        } else {
            $resultadoFinal = 0;
        }

        return $resultadoFinal;
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
