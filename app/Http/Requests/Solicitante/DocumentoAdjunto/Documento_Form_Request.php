<?php

namespace App\Http\Requests\Solicitante\DocumentoAdjunto;

use Illuminate\Foundation\Http\FormRequest;

class Documento_Form_Request extends FormRequest
{
    public function rules()
    {
        return [
            'documento_cronograma' => 'required|file|mimes:pdf,xlsx,xls|max:10240',
            'documento_desembolso' => 'required|file|mimes:pdf,xlsx,xls|max:10240',
            'tipo_documento_id_cronograma' => 'required|integer',
            'tipo_documento_id_desembolso' => 'required|integer',
            'tipo_documento_id' => 'integer',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
