<?php

namespace App\Http\Requests\Solicitante;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudRiocpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'monto_total' => 'required|numeric',
            'plazo' => 'required|numeric',
            'interes_anual' => 'required|numeric',
            'declaracion_jurada' => 'required|string',
            'comision_concepto' => 'nullable',
            'comision_tasa' => 'nullable',
            'periodo_gracia' => 'required|numeric',
            'objeto_operacion_credito' => 'required|string|max:255',
            'firma_digital' => 'required|boolean',
            'ruta_documento' => 'string|max:255',
            'documento' => 'required|file|mimes:pdf|max:10240',
            'acreedor_id' => 'required|exists:acreedores,id',
            'moneda_id' => 'required|exists:monedas,id',
            'entidad_id' => 'required|exists:entidades,id',
            'identificador_id' => 'required|exists:identificadores_credito,id',
            'periodo_id' => 'required|exists:periodos,id',
            'nombre_completo' => 'required|string|max:255',
            'correo_electronico' => 'required|email|max:255',
            'cargo' => 'required|string|max:255',
            'telefono' => 'required|integer'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
