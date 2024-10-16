<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{

    public function index()
    {
        $roles = Menu::all();

        if ($roles->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay listado de menú.',
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
            'nombre' => 'required|string|min:4|max:50',
            'url' => 'required|string',
            'icono' => 'required|string',
            'rol' => 'required|integer',
            'show_menu' => 'required|boolean',
            'estado' => 'required|boolean'
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $rol = new Menu($request->input());
        $rol->save();

        return response()->json([
            'status' => true,
            'message' => 'Menú Creado.'
        ], 200);
    }

    public function show($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'status' => false,
                'message' => 'Menu no encontrado.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $menu
        ]);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'status' => false,
                'message' => 'Menú no encontrado.',
            ], 404);
        }

        $rules = [
            'nombre' => 'required|string|min:4|max:50',
            'url' => 'required|string',
            'icono' => 'required|string',
            'rol' => 'required|integer',
            'show_menu' => 'required|boolean',
            'estado' => 'required|boolean'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $menu->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Menú actualizado correctamente.',
            'data' => $menu
        ], 200);
    }

    public function deleteRol($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return response()->json([
                'status' => false,
                'message' => 'Menú no encontrado.'
            ], 404);
        }

        $menu->estado = 0;
        $menu->save();

        return response()->json([
            'status' => true,
            'message' => 'Menú desactivado correctamente.',
            'data' => $menu
        ], 200);
    }

    public function selectMenuByRol()
    {
        $user = Auth::user();

        if ($user && $user->rol_id) {
            $menu = Menu::select(
                'menus.id',
                'menus.nombre',
                'menus.url',
                'menus.icono',
                'menus.rol_id',
                'menus.tipo_id',
                'menus.show_menu',
                'roles.rol',
                'tipos.tipo'
            )
                ->where('menus.estado', true)
                ->where('menus.rol_id', $user->rol_id) 
                ->join('roles', 'menus.rol_id', '=', 'roles.id')
                ->join('tipos', 'menus.tipo_id', '=', 'tipos.id')
                ->get();

            if ($menu->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Menú Rol no encontrado.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Menú rol',
                'data' => $menu
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
