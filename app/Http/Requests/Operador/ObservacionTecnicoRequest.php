<?php

namespace App\Http\Requests\Operador;

use Illuminate\Foundation\Http\FormRequest;

class ObservacionTecnicoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'observaciones' => 'required|array',
            'observaciones.*.cumple' => 'required|boolean',
            'observaciones.*.observacion' => 'required|string',
            'observaciones.*.tipo_observacion_id' => 'required|integer',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
