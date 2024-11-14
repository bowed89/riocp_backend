<?php

namespace App\Http\Requests\Revisor;

use Illuminate\Foundation\Http\FormRequest;

class SubirHistorialRequests extends FormRequest
{
    public function rules()
    {
        return [
            'tipo_documento_id' => 'required|integer',
            'file' => 'required|file'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
