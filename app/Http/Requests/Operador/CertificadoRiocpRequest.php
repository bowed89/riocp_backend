<?php

namespace App\Http\Requests\Operador;

use Illuminate\Foundation\Http\FormRequest;

class CertificadoRiocpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nro_solicitud' => 'nullable|integer',
            'servicio_deuda' => 'required|numeric',
            'valor_presente_deuda_total' => 'required|numeric',
            'solicitud_id' => 'required|integer',
            'objeto_operacion_credito' => 'required|string',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
