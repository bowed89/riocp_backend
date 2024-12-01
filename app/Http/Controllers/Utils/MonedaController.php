<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Moneda;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    public function index()
    {
        $monedas = Moneda::all();

        if ($monedas->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay monedas disponibles.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Listado de monedas.',
            'data' => $monedas,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'tipo' => 'required|string',
            'sigla' => 'required|string',
            'cambio' => 'required|numeric',
            'estado' => 'required|boolean'
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $moneda = new Moneda($request->input());
        $moneda->save();

        return response()->json([
            'status' => true,
            'message' => 'Moneda Creada correctamente.'
        ], 200);
    }
    
    public function update(Request $request, $id)
    {
        $moneda = Moneda::find($id);

        if (!$moneda) {
            return response()->json([
                'status' => false,
                'message' => 'Moneda no encontrada.',
            ], 404);
        }

        $rules = [
            'tipo' => 'required|string',
            'sigla' => 'required|string',
            'cambio' => 'required|numeric',
            'estado' => 'required|boolean'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $moneda->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Moneda actualizada correctamente.',
            'data' => $moneda
        ], 200);
    }



    public function deleteMoneda($id)
    {
        $moneda = Moneda::find($id);

        if (!$moneda) {
            return response()->json([
                'status' => false,
                'message' => 'Moneda no encontrada.'
            ], 404);
        }

        $moneda->estado = 0;
        $moneda->save();

        return response()->json([
            'status' => true,
            'message' => 'Moneda desactivada correctamente.',
            'data' => $moneda
        ], 200);
    }

    public function showById($id)
    {
        $moneda = Moneda::find($id);

        return response()->json([
            'status' => true,
            'data' => $moneda,
        ], 200);
    }

}
