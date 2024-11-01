<?php

namespace App\Http\Requests\Revisor;

use Illuminate\Foundation\Http\FormRequest;

class SeguimientoRevisorRequests extends FormRequest
{
    public function rules()
    {
        return [
            'id_seguimiento' => 'required|integer',
            'observacion' => 'required|string',
            'solicitud_id' => 'required|integer',
            'usuario_destino_id' => 'required|integer',
            // Observaciones
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
