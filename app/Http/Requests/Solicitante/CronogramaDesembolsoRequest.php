<?php

namespace App\Http\Requests\Solicitante;

use Illuminate\Foundation\Http\FormRequest;

class CronogramaDesembolsoRequest extends FormRequest
{
    public function rules()
    {
        return [
            // Reglas para cronograma_desembolsos
            'cronograma_desembolsos' => 'required|array',
            'cronograma_desembolsos.*.objeto_deuda' => 'required|string',
            'cronograma_desembolsos.*.monto_contratado_a' => 'required|numeric',
            'cronograma_desembolsos.*.monto_desembolsado_b' => 'required|numeric',
            'cronograma_desembolsos.*.saldo_desembolso_a_b' => 'required|numeric',
            'cronograma_desembolsos.*.desembolso_desistido' => 'required|boolean',
            'cronograma_desembolsos.*.acreedor_id' => 'required|integer',
            // Reglas para fecha_desembolsos dentro de cronograma_desembolsos
            'cronograma_desembolsos.*.fecha_desembolsos' => 'array',
            'cronograma_desembolsos.*.fecha_desembolsos.*.fecha' => 'date',
            'cronograma_desembolsos.*.fecha_desembolsos.*.monto' => 'numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
