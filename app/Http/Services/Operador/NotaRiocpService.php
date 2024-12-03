<?php

namespace App\Http\Services\Operador;

use App\Http\Services\Utils\GenerarNotasRiocp;
use App\Models\CertificadoRiocp;
use App\Models\Solicitud;
use App\Models\SolicitudRiocp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use stdClass;

class NotaRiocpService
{
    // GUARDAR CERTIFICADO RIOCP NO APROBADO
    public function almacenarCertificadoReprobado($request)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        $solicitud = Solicitud::where('id', $request['solicitud_id'])->first();
        if (!$solicitud) {
            return [
                'status' => false,
                'message' => 'No existe la solicitud requerida.'
            ];
        }

        // este es el formulario 1
        $solicitudRiocp = SolicitudRiocp::where('solicitud_id', $request['solicitud_id'])
            ->first();

        if (!$solicitudRiocp) {
            return [
                'status' => false,
                'message' => 'No existe el formulario de solicitud RIOCP con el número de solicitud.'
            ];
        }

        // verifico si estoy dentro de rangos de 
        // Servicio Deuda y Valor Presente Deuda Total
        $interesAnual = $request['servicio_deuda'];
        $interesAnual = (float) $interesAnual;
        $valorPresenteDeuda = $request['valor_presente_deuda_total'];
        $valorPresenteDeuda = (float) $valorPresenteDeuda;

        $certificado = new CertificadoRiocp();
        $certificado->fill($request);

        // actualizo el campo objeto_operacion... de la tabla solicitud riocp,
        // en caso de q se quiera corregir la tabla solicitada
        $solicitudRiocp->objeto_operacion_credito = $request['objeto_operacion_credito'];
        $solicitudRiocp->save();

        if ($interesAnual > 20.00 && $valorPresenteDeuda > 200.00) {
            // nuevo certificado RECHAZADO = 2
            $certificado->estados_riocp_id = 2;
            $certificado->nro_solicitud = 0;
            $certificado->save();

            // cambio de estado mi solicitud RECHAZADO = 2
            $solicitud->estado_solicitud_id = 2;
            $solicitud->save();

            return [
                'status' => true,
                'message' => 'Certificado almacenado correctamente con valores de Servicio Deuda y Valor Presente Deuda Total fuera de los rangos.'
            ];
        }
    }

    public function verNotas($solicitudId)
    {
        $servicioDeuda = new ServicioDeudaService();
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        // llamo el query para obtener mi codigo_entidad
        $certificadoRiocpService = new CertificadoRiocpService();
        $query = $certificadoRiocpService->obtenerSolicitudCertificado($solicitudId);

        if (!$query) {
            return [
                'status' => false,
                'message' => 'No se encontraron solicitudes.'
            ];
        }


        // obtengo el codigo_entidad de la consulta
        $codigo_entidad = $query['data'][0]->codigo;
        $sd = $servicioDeuda->obtenerServicioDeuda($codigo_entidad);

        //DATO QUEMADO DE VPD
        $vpd = $certificadoRiocpService->obtenerValorPresenteDeudaTotal($codigo_entidad);

        Log::debug("vpd" .$vpd);

        
        $generarNota = new GenerarNotasRiocp();

        $body = new stdClass();
        /*  $body->fechaActual = $generarNota->fechaActualNota(); //header
        $body->destinatarioNota = $generarNota->destinatarioNota($solicitudId); //header */
        $body->fecha = $generarNota->fechaActualNota();
        $body->nro_nota = $generarNota->nroNota();

        $body->header = $generarNota->destinatarioNota($solicitudId);

        $body->referencia = $generarNota->Referencia();

        Log::debug("sd =>" .$sd);
        Log::debug("vpd =>" .$vpd);

        $body->body = $generarNota->body($solicitudId, $sd, $vpd);
        $body->remitente = $generarNota->remitente();
        $body->revisado = $generarNota->revisado();
        
        return [
            'status' => true,
            'data' => $body,
            'message' => 'Nota solicitada.'
        ];
    }
}
