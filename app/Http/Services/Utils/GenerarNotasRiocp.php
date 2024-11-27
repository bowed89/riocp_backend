<?php


namespace App\Http\Services\Utils;

use App\Models\SolicitudRiocp;
use Carbon\Carbon;

class GenerarNotasRiocp
{

    public function fechaActualNota()
    {
        // La Paz, 26 de noviembre de 2024
        $fecha = Carbon::now();
        $anio = $fecha->year;
        $mes = $fecha->translatedFormat('F');
        $dia = (int) $fecha->format('d');
        return 'La Paz, ' . $dia . ' de ' . $mes . ' de ' . $anio;
    }

    public function fechaPersonalizada($fecha)
    {
        // 26 de noviembre de 2024
        $date = Carbon::parse($fecha);
        $anio = $date->year;
        $mes = $date->translatedFormat('F');
        $dia = (int) $date->format('d');
        return $dia . ' de ' . $mes . ' de ' . $anio;
    }

    public function destinatarioNota($solicitudId)
    {
        $solicitud = $this->queryObtenerDatosNota($solicitudId);
        return 'Señor' . "\n\n" .
            $solicitud->nombre_completo  . "\n" .
            $solicitud->cargo . "\n" .
            $solicitud->denominacion . "\n" .
            'Cuidad/Municipio - Departamento.-';
    }

    public function Referencia()
    {
        return 'Ref.:' . "\n" . '<b>Certificado de Registro de Inicio de Operaciones de Crédito Público</b>';
    }

    public function body($solicitudId, $sd, $vpd)
    {
        $consulta = $this->queryObtenerDatosNota($solicitudId);
        $fechaCorrespondencia = $this->fechaPersonalizada($consulta->fecha_correspondencia);
        $objetoOperacion = ucwords(strtolower($consulta->objeto_operacion_credito));
        $objetoOperacionFinal = '"<i>' . $objetoOperacion . '</i>"';
        $acreedor = ucwords(strtolower($consulta->nombre_acreedor));

        // verifico si estoy dentro de rangos de 
        // Servicio Deuda y Valor Presente Deuda Total
        $servicioDeuda = (float) $sd;
        $valorPresenteDeuda = (float) $vpd;
        $motivoRechazo = null;

        if ($valorPresenteDeuda > 200.00 && $servicioDeuda < 20.00) {
            $motivoRechazo = 'indicador de 
            Valor Presente de la Deuda total supera el límite establecido en la Ley N° 2042 de Administración Presupuestaria (200%)';
        }

        if ($servicioDeuda > 20.00 && $valorPresenteDeuda < 200.00) {
            $motivoRechazo = 'indicador del Servicio de Deuda supera 
            el límite establecido en la Ley N° 2042 de Administración Presupuestaria (20%)';
        }

        if ($servicioDeuda > 20.00 && $valorPresenteDeuda > 200.00) {
            $motivoRechazo = 'indicador del Servicio de Deuda y el Valor Presente
             de la Deuda total superan el límite establecido en la
             Ley N° 2042 de Administración Presupuestaria (20%) (200%)';
        }

        return '
        De mi consideración:
        Cursa en este despacho la nota ' . $consulta->cite_documento . ' de ' . $fechaCorrespondencia . ',
        mediante la cual el ' . $consulta->denominacion . ', solicita 
        el certificado de Registro de Inicio de Operaciones de Crédito Público en el marco
        de la Resolución Ministerial N° 338 de 29 de septiembre de 2022, que aprueba el 
        “Reglamento Específico para el Registro de Inicio de Operaciones de Crédito Público
        para Proyectos de Inversión Pública” modificada mediante 
        Resolución Ministerial N° 006 de 10 de enero de 2024, para la contratación de un nuevo 
        endeudamiento destinado a la ' . $objetoOperacionFinal . ',
        a ser financiado por el' . $acreedor . '.
        Al respecto, cabe señalar que, en el marco de la normativa vigente, 
        con la inclusión del trámite solicitado por su entidad, el' . $motivoRechazo . ' , 
        mientras dicho indicador no se encuentre dentro del límite mencionado, el GAM ARA en la presente
        gestión no está en condiciones de asumir la nueva obligación de endeudamiento público para el citado proyecto.
        Con este motivo, saludo a usted atentamente.';
    }

    public function queryObtenerDatosNota($solicitudId)
    {
        return  SolicitudRiocp::query()
            ->join('contactos_subsanar AS cs', 'cs.id', '=', 'solicitudes_riocp.contacto_id')
            ->join('entidades AS e', 'e.id', '=', 'solicitudes_riocp.entidad_id')
            ->join('solicitudes AS sol', 'sol.id', '=', 'solicitudes_riocp.solicitud_id')
            ->join('formularios_correspondencia AS f', 'f.solicitud_id', '=', 'sol.id')
            ->join('acreedores AS a', 'a.id', '=', 'solicitudes_riocp.acreedor_id')
            ->where('solicitudes_riocp.solicitud_id', $solicitudId)
            ->select(
                'cs.nombre_completo',
                'cs.cargo',
                'e.denominacion AS denominacion',
                's.objeto_operacion_credito',
                'a.nombre AS nombre_acreedor',
                'f.cite_documento',
                'f.created_at AS fecha_correspondencia'
            )->first();
    }

    public function footer()
    {
        return 'Viceministra del Tesoro y Crédito Público';
    }

}
