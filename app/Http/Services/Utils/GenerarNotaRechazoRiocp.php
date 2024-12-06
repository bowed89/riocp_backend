<?php


namespace App\Http\Services\Utils;

use App\Models\SolicitudRiocp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerarNotaRechazoRiocp
{

    public function fechaActualNota()
    {
        // La Paz, 26 de noviembre de 2024
        $fecha = Carbon::now();
        $anio = $fecha->year;
        $mes = $fecha->translatedFormat('F');
        $dia = (int) $fecha->format('d');
        return 'La Paz, ' . $dia . ' de ' . $mes . ' de ' . $anio . "\n";
    }

    public function nroNota()
    {
        //  MEFP/VTCP/DGAFT/USCFT/N°711/2024
        $fecha = Carbon::now();
        $anio = $fecha->year;
        return 'MEFP/VTCP/DGAFT/USCFT/N°711/' . $anio;
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
            $solicitud->declaracion_jurada  . "\n" .
            'Alcalde' . "\n" .
            $solicitud->denominacion . "\n" .
            'Cuidad/Municipio - Departamento.-';
    }

    public function Referencia()
    {
        return 'Ref.:' . "\n" . 'Certificado de Registro de Inicio de Operaciones de Crédito Público';
    }

    public function body($solicitudId, $sd, $vpd)
    {
        $consulta = $this->queryObtenerDatosNota($solicitudId);
        $fechaCorrespondencia = $this->fechaPersonalizada($consulta->fecha_correspondencia);
        $objetoOperacion = ucwords(strtolower($consulta->objeto_operacion_credito));
        $acreedor = ucwords(strtolower($consulta->nombre_acreedor));

        // verifico si estoy dentro de rangos de 
        // Servicio Deuda y Valor Presente Deuda Total
        $servicioDeuda = (float)$sd;
        $valorPresenteDeuda = (float)$vpd;

        $motivoRechazo = '';
        // Debug para verificar los valores
        Log::debug("Valor Presente Deuda => " . $valorPresenteDeuda);
        Log::debug("Servicio Deuda => " . $servicioDeuda);

        if ($valorPresenteDeuda > 200 && $servicioDeuda < 20.00) {
            $motivoRechazo = 'indicador de 
            Valor Presente de la Deuda total supera el límite establecido en la Ley N° 2042 de Administración Presupuestaria (200%)';
        } elseif ($servicioDeuda > 20.00 && $valorPresenteDeuda < 200) {
            $motivoRechazo = 'indicador del Servicio de Deuda supera 
            el límite establecido en la Ley N° 2042 de Administración Presupuestaria (20%)';
        } elseif ($servicioDeuda > 20.00 && $valorPresenteDeuda > 200) {
            $motivoRechazo = 'indicador del Servicio de Deuda y el Valor Presente
            de la Deuda total superan el límite establecido en la
            Ley N° 2042 de Administración Presupuestaria (20%) (200%)';
        }

        Log::debug("motivoRechazo =>" . $motivoRechazo);

        return '
        De mi consideración:
        Cursa en este despacho la nota ' . $consulta->cite_documento . ' de ' . $fechaCorrespondencia . ',
        mediante la cual el ' . $consulta->denominacion . ', solicita 
        el certificado de Registro de Inicio de Operaciones de Crédito Público en el marco
        de la Resolución Ministerial N° 338 de 29 de septiembre de 2022, que aprueba el 
        “Reglamento Específico para el Registro de Inicio de Operaciones de Crédito Público
        para Proyectos de Inversión Pública” modificada mediante 
        Resolución Ministerial N° 006 de 10 de enero de 2024, para la contratación de un nuevo 
        endeudamiento destinado a la ' . $objetoOperacion . ',
        a ser financiado por el' . $acreedor . '.
        Al respecto, cabe señalar que, en el marco de la normativa vigente, 
        con la inclusión del trámite solicitado por su entidad, el' . $motivoRechazo . ' , 
        mientras dicho indicador no se encuentre dentro del límite mencionado, el GAM ARA en la presente
        gestión no está en condiciones de asumir la nueva obligación de endeudamiento público para el citado proyecto.
        Con este motivo, saludo a usted atentamente.';
    }

    public function queryObtenerDatosNota($solicitudId)
    {
        return SolicitudRiocp::from('solicitudes_riocp AS s')
            ->join('contactos_subsanar AS cs', 'cs.id', '=', 's.contacto_id')
            ->join('entidades AS e', 'e.id', '=', 's.entidad_id')
            ->join('solicitudes AS sol', 'sol.id', '=', 's.solicitud_id')
            ->join('formularios_correspondencia AS f', 'f.solicitud_id', '=', 'sol.id')
            ->join('acreedores AS a', 'a.id', '=', 's.acreedor_id')
            ->where('s.solicitud_id', $solicitudId)
            ->select(
                'cs.nombre_completo',
                'cs.cargo',
                'e.denominacion AS denominacion',
                's.objeto_operacion_credito',
                's.declaracion_jurada',
                'a.nombre AS nombre_acreedor',
                'f.cite_documento',
                'f.created_at AS fecha_correspondencia'
            )->first();
    }


    public function remitente()
    {
        return 'Viceministra del Tesoro y Crédito Público';
    }

    public function revisado()
    {
        return 'H.R.: 2024-18378-R' . "\n" . 'JPJS/TAE/JCVL/Shiarella Zelaya - Yamil Venegas' . "\n" . 'C.c.: Archivo';
    }
}
