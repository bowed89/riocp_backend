<?php

namespace App\Http\Requests\Solicitante;

use Illuminate\Foundation\Http\FormRequest;

class CronogramaServicioDeudaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'acreedor_id' => 'required|integer',
            'objeto_deuda' => 'required|string',
            'moneda_id' => 'required|integer',
            'total_capital' => 'required|numeric',
            'total_saldo' => 'required|numeric',
            'total_interes' => 'required|numeric',
            'total_comisiones' => 'required|numeric',
            'total_sum' => 'required|numeric',
            // cuadros_pagos: valido un array de objetos
            'cuadro_pagos' => 'required|array',
            'cuadro_pagos.*.fecha_vencimiento' => 'required|date',
            'cuadro_pagos.*.capital' => 'required|numeric',
            'cuadro_pagos.*.interes' => 'required|numeric',
            'cuadro_pagos.*.comisiones' => 'required|numeric',
            'cuadro_pagos.*.total' => 'required|numeric',
            'cuadro_pagos.*.saldo' => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
