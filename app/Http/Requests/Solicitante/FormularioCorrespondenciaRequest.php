<?php

namespace App\Http\Requests\Solicitante;

use Illuminate\Foundation\Http\FormRequest;

class FormularioCorrespondenciaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre_completo' => 'required|string|max:255',
            'correo_electronico' => 'required|email',
            'nombre_entidad' => 'required|string|max:255',
            'cite_documento' => 'nullable|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'documento' => 'nullable|file|mimes:pdf|max:10240',
            'firma_digital' => 'nullable|string|max:255',
            'solicitud_id' => 'nullable|exists:solicitudes,id',
        ];
    }
}
