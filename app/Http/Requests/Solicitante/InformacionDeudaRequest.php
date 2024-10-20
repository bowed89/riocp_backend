<?php

namespace App\Http\Requests\Solicitante;

use Illuminate\Foundation\Http\FormRequest;

class InformacionDeudaRequest extends FormRequest
{

    public function rules()
    {
        return [
            'pregunta_1' => 'required|boolean',
            'pregunta_2' => 'required|boolean',
            'pregunta_3' => 'required|boolean',
            'pregunta_4' => 'required|boolean',
            'solicitud_id' => 'required|integer',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
