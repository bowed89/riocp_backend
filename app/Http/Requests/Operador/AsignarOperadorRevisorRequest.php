<?php

namespace App\Http\Requests\Operador;

use Illuminate\Foundation\Http\FormRequest;

class AsignarOperadorRevisorRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id_seguimiento' => 'required|integer',
            'observacion' => 'required|string',
            'solicitud_id' => 'required|integer',
            'usuario_destino_id' => 'required|integer',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
