<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SolicitudController extends Controller
{
    public function index()
    {
        $solicitudes = Solicitud::all();

        if ($solicitudes->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay listado de solicitudes.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $solicitudes,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nro_solicitud' => 'string',
            'usuario_id' => 'required|integer'
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $solicitud = new Solicitud($request->input());
        $solicitud->save();

        return response()->json([
            'status' => true,
            'message' => 'Solicitud Creada.',
            'data' => $solicitud
        ], 200);
    }
}
