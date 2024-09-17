<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|string|email|max:100|unique:usuarios',
            'nombre_usuario' => 'required|string|max:50|unique:usuarios',
            'ci' => 'required|integer|unique:usuarios',
            'password' => 'required|string|min:5',
            'estado' => 'required|boolean',
            'rol_id' => 'required|integer',
            'entidad_id' => 'nullable|integer',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $user = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'nombre_usuario' => $request->nombre_usuario,
            'ci' => $request->ci,
            'password' => Hash::make($request->password),
            'estado' => $request->estado,
            'rol_id' => $request->rol_id,
            'entidad_id' => $request->entidad_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Usuario Creado.',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    public function login(Request $request)
    {
        $rules = [
            'correo' => 'required|string|email|max:100',
            'password' => 'required|string|min:5',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $auth = Auth::attempt($request->only('correo', 'password'));

        if (!$auth) {
            return response()->json([
                'status' => false,
                'errors' => 'Error al autenticarse. Por favor verifique sus datos.'
            ], 401);
        }

        $user = Usuario::where('correo', $request->correo)->first();

        return response()->json([
            'status' => true,
            'message' => 'Usuario Autenticado correctamente.',
            'data' => $user,
            'token' => $user->createtoken('API TOKEN')->plainTextToken
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'errors' => 'Se cerro sesión.'
        ], 200);
    }

    public function allUsers()
    {
        $users = Usuario::select(
            'usuarios.id',
            'usuarios.nombre',
            'usuarios.apellido',
            'usuarios.correo',
            'usuarios.nombre_usuario',
            'roles.rol',
            'usuarios.estado',
            'usuarios.rol_id',
            'usuarios.entidad_id'
        )
            ->join('roles', 'usuarios.rol_id', '=', 'roles.id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Listado de Usuarios.',
            'data' => $users,
        ], 200);
    }
}
