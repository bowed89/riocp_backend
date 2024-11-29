<?php

namespace App\Imports;

use App\Models\DeudaPublicaExterna;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeudaPublicaExternaImport implements ToModel, WithHeadingRow
{
    public function __construct()
    {
        // DeudaPublicaExterna::truncate();
    }

    public function model(array $row)
    {
        Log::debug('Row data: ' . json_encode($row));
        // valido si la fila tiene datos necesarios
        if (
            empty($row['credito']) ||
            empty($row['codigo']) ||
            empty($row['entidad']) ||
            empty($row['acreedor']) ||
            empty($row['prestamo']) ||
            empty($row['proyecto'])
        ) {
            return null;
        }



        return new DeudaPublicaExterna([
            'credito' => $row['credito'] ?? null,
            'codigo' => $row['codigo'] ?? null,
            'entidad' => $row['entidad'] ?? null,
            'acreedor' => $row['acreedor'] ?? null,
            'prestamo' => $row['prestamo'] ?? null,
            'proyecto' => $row['proyecto'] ?? null,
            'monto_autorizado_riocp' => $row['monto_autorizado_riocp'] ?? 0,
            'monto_contratado' => $row['monto_contratado'] ?? 0,
            'monto_prestamo' => $row['monto_prestamo'] ?? 0,
            'monto_desembolsado' => $row['monto_desembolsado'] ?? 0,
            'saldo_por_desembolsar' => $row['saldo_por_desembolsar'] ?? 0,
            'plazo_anos' => $row['plazo_anos'] ?? 0,
            'gracia' => $row['gracia'] ?? 0,
            'tasa_de_interes' => $row['tasa_de_interes'] ?? 0,
            'comision' => $row['comision'] ?? 0,
            'fecha_cuota' => $row['fecha_cuota'] ?? null,
            'capital_moneda_origen' => $row['capital_en_moneda_origen'] ?? 0,
            'interes_moneda_origen' => $row['interes_moneda_origen'] ?? 0,
            'comision_moneda_origen' => $row['comision_moneda_origen'] ?? 0,
            'cuota_moneda_origen' => $row['cuota_en_moneda_origen'] ?? null,
            'estado_prestamo' => $row['estado_del_prestamo'] ?? null,
            'moneda_origen' => $row['moneda_de_origen'] ?? null,
            'tipo_cambio_sriocp' => $row['tipo_de_cambio_sriocp'] ?? 0,
            'tipo_cambio_valor' => $row['tipo_de_cambio_valor'] ?? 0,
            'fecha_del_tipo_de_cambio_del_tramite' => $row['fecha_del_tipo_de_cambio_del_tramite'] ?? null,
            'tipo_cambio_dinamico' => $row['tipo_de_cambio_dinamico'] ?? 0,
            'monto_autorizado_bs' => $row['monto_autorizado_en_bs'] ?? 0,
            'monto_contratado_bs' => $row['monto_contratado_en_bs'] ?? 0,
            'monto_prestamo_bs' => $row['monto_prestamo_en_bs'] ?? 0,
            'monto_desembolsado_bs' => $row['monto_desembolsado_en_bs'] ?? 0,
            'capital_bs' => $row['capital_en_bs'] ?? 0,
            'interes_bs' => $row['interes_en_bs'] ?? 0,
            'comision_bs' => $row['comision_en_bs'] ?? 0,
            'codigo_riocp' => $row['codigo_riocp'] ?? null,
            'fecha_emision_certificado_riocp' => $row['fecha_de_emision_del_certificado_de_riocp'] ?? null,
            'fecha_vigencia' => $row['fech_de_vigencia'] ?? null,
            'gestion' => $row['gestion'] ?? 0,
            'meses' => $row['meses'] ?? 0,
            'si' => $row['si'] ?? null,
            'actualizacion_mensual_fndr' => $row['actualizacion_mensual_fndr'] ?? null,
        ]);
    }
    /**
     * define que las cabeceras están en la fila 4 del archivo
     */
    public function headingRow(): int
    {
        return 1;
    }
}
