<?php


namespace App\Http\Services\Utils;

use App\Models\SolicitudRiocp;
use Carbon\Carbon;

class GenerarNotaObservacionRiocp
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

    public function body($solicitudId)
    {
        $consulta = $this->queryObtenerDatosNota($solicitudId);

        return '
        De mi consideración:
        Hago referencia a nota CITE: ' . $consulta->cite_documento . ' , relacionada a su solicitud de
        certificado de Registro de Inicio de Operaciones de Crédito Público (RIOCP).' . "\n" .

            'Al respecto, una vez analizada la información financiera en el marco de la Resolución 
        Ministerial N°338 de 29 de septiembre de 2022, que aprueba el “Reglamento Específico para
         el Registro de Inicio de Operaciones de Crédito Público para Proyectos de Inversión Pública”, 
         modificado mediante Resolución Ministerial N° 006 de 10 de enero de 2024, adjunto a la presente 
         remito a usted el detalle de observaciones que impiden el procesamiento
        del trámite de referencia.' . "\n" .

            'Asimismo, es menester recordarle que conforme lo dispuesto en el Parágrafo II, Artículo 4 del 
        Reglamento mencionado precedentemente, cualquier inconsistencia que derive en la demora de la 
        emisión del resultado correspondiente al RIOCP, es responsabilidad de la Máxima Autoridad
        Ejecutiva de la Entidad Solicitante del certificado de RIOCP para la operación 
         de crédito público.' . "\n" .

            'Finalmente,' . $consulta->denominacion . 'estará habilitado
        para efectuar la operación de crédito público, una vez que el Viceministerio del Tesoro 
        y Crédito Público emita el resultado de la solicitud realizada.' . "\n" .
            'Con este motivo, saludo a usted atentamente.';
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
