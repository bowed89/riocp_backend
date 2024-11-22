<?php

namespace App\Http\Requests\JefeUnidad;

use Illuminate\Foundation\Http\FormRequest;

class SeguimientoJefeUnidadRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id_seguimiento' => 'required|integer',
            'observacion' => 'required|string',
            'nro_hoja_ruta' => 'string',
            'solicitud_id' => 'required|integer',
            'usuario_destino_id' => 'required|integer',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
