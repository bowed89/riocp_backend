<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::all();

        if ($roles->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay roles disponibles.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $roles,
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'rol' => 'required|string|min:4|max:50',
            'descripcion' => 'string|min:4',
            'estado' => 'required|boolean',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $rol = new Rol($request->input());
        $rol->save();
        
        return response()->json([
            'status' => true,
            'message' => 'Rol Creado.'
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'status' => false,
                'message' => 'Rol no encontrado.',
            ], 404);
        }

        $rules = [
            'rol' => 'required|string|min:4|max:50',
            'descripcion' => 'string|min:4',
            'estado' => 'required|boolean'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $rol->update($request->all());


        return response()->json([
            'status' => true,
            'message' => 'Rol actualizado correctamente.',
            'data' => $rol
        ], 200);
    }


    public function destroy(Rol $rol) {}

    public function deleteRol($id)
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'status' => false,
                'message' => 'Rol no encontrado.'
            ], 404);
        }

        $rol->estado = 0;
        $rol->save();

        return response()->json([
            'status' => true,
            'message' => 'Rol desactivado correctamente.',
            'data' => $rol
        ], 200);
    }

    public function showById($id)
    {
        $rol = Rol::find($id);

        return response()->json([
            'status' => true,
            'data' => $rol,
        ], 200);
    }
}
