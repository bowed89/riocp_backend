<?php

namespace App\Http\Controllers;

use App\Models\FormularioCorrespondencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormularioCorrespondenciaController extends Controller
{
    public function index()
    {
        $fomularios = FormularioCorrespondencia::all();

        if ($fomularios->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay formularios registrados.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $fomularios,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre_completo' => 'required|string|min:5',
            'correo_electronico' => 'required|email|max:255',
            'nombre_entidad' => 'required|string|max:255',
            'cite_documento' => 'string|max:255',
            'referencia' => 'string|max:255',
            'ruta_documento' => 'string|max:255',
            'documento_firmado' => 'required|boolean',
            'estado' => 'required|boolean',
            'solicitud_id' => 'required|integer'
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $formulario = new FormularioCorrespondencia($request->input());
        $formulario->save();

        return response()->json([
            'status' => true,
            'message' => 'Formulario Creado.'
        ], 200);
    }


    public function show($id)
    {
        $formulario = FormularioCorrespondencia::find($id);

        if (!$formulario) {
            return response()->json([
                'status' => false,
                'message' => 'Formulario no encontrado.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $formulario
        ]);
    }

    public function update(Request $request, $id)
    {
        $formulario = FormularioCorrespondencia::find($id);

        if (!$formulario) {
            return response()->json([
                'status' => false,
                'message' => 'Formulario no encontrado.',
            ], 404);
        }

        $rules = [
            'nombre_completo' => 'string|min:5',
            'correo_electronico' => 'email|max:255',
            'nombre_entidad' => 'string|max:255',
            'cite_documento' => 'string|max:255',
            'referencia' => 'string|max:255',
            'ruta_documento' => 'string|max:255',
            'documento_firmado' => 'required|boolean',
            'estado' => 'boolean',
            'solicitud_id' => 'integer'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $formulario->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Formulario actualizado correctamente.',
            'data' => $formulario
        ], 200);
    }

    public function deleteFormulario($id)
    {
        $formulario = FormularioCorrespondencia::find($id);

        if (!$formulario) {
            return response()->json([
                'status' => false,
                'message' => 'Formulario no encontrado.'
            ], 404);
        }

        $formulario->estado = 0;
        $formulario->save();

        return response()->json([
            'status' => true,
            'message' => 'Formulario desactivado correctamente.',
            'data' => $formulario
        ], 200);
    }
}
