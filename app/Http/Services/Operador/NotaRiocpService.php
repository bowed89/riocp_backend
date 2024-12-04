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
    // GUARDO CERTIFICADO RIOCP NO APROBADO
    public function almacenarNota()
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        

        
    }

    public function verNotas($solicitudId)
    {
        $servicioDeuda = new ServicioDeudaService();
        $valorPresenteDeudaService = new ValorPresenteDeudaService();

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

        // VPD
        $vpd = $valorPresenteDeudaService->obtenerValorPresenteDeudaTotal($codigo_entidad);
        Log::debug("vpd" . $vpd);

        $generarNota = new GenerarNotasRiocp();

        $body = new stdClass();
        /*  $body->fechaActual = $generarNota->fechaActualNota(); //header
        $body->destinatarioNota = $generarNota->destinatarioNota($solicitudId); //header */
        $body->fecha = $generarNota->fechaActualNota();
        $body->nro_nota = $generarNota->nroNota();

        $body->header = $generarNota->destinatarioNota($solicitudId);

        $body->referencia = $generarNota->Referencia();

        Log::debug("sd =>" . $sd);
        Log::debug("vpd =>" . $vpd);

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
