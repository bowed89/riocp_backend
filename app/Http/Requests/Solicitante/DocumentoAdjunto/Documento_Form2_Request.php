<?php

namespace App\Http\Requests\Solicitante\DocumentoAdjunto;

use Illuminate\Foundation\Http\FormRequest;

class Documento_Form2_Request extends FormRequest
{
    public function rules()
    {
        return [
            'documento' => 'required|file|mimes:pdf,xlsx,xls|max:10240',
            'tipo_documento_id' => 'required|integer',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
