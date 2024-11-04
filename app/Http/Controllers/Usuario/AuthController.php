<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|string|email|max:100|unique:usuarios',
            'nombre_usuario' => 'required|string|max:50|unique:usuarios',
            'ci' => 'required|integer|min:5|unique:usuarios',
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
            'created_at' => Carbon::now()
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Usuario Creado.',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    public function show($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $usuario
        ]);
    }

    public function getTecnicos()
    {
        $usuarios = Usuario::where('rol_id', 3)->get();

        if (!$usuarios) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario técnico no encontrado.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $usuarios
        ]);
    }

    public function getRevisores()
    {
        $usuarios = Usuario::where('rol_id', 4)->get();

        if (!$usuarios) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario revisor no encontrado.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $usuarios
        ]);
    }

    public function getDGAFT()
    {
        $usuarios = Usuario::where('rol_id', 5)->get();

        if (!$usuarios) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario DGAFT no encontrado.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $usuarios
        ]);
    }



    public function getJefeUnidad()
    {
        $usuarios = Usuario::where('rol_id', 2)->get();

        if (!$usuarios) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario jefe de unidad no encontrado.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $usuarios
        ]);
    }


    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        $rules = [
            'nombre' => 'string|max:100',
            'apellido' => 'string|max:100',
            'correo' => [
                'string',
                'email',
                'max:100',
                // Excluir el usuario actual de la validación de unicidad
                'unique:usuarios,correo,' . $id,
            ],
            'nombre_usuario' => [
                'string',
                'max:50',
                // Excluir el usuario actual de la validación de unicidad
                'unique:usuarios,nombre_usuario,' . $id,
            ],
            'ci' => [
                'integer',
                // Excluir el usuario actual de la validación de unicidad
                'unique:usuarios,ci,' . $id,
            ],
            'password' => 'nullable|string|min:5',
            'estado' => 'boolean',
            'rol_id' => 'integer',
            'entidad_id' => 'nullable|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $data = $request->only(['nombre', 'apellido', 'correo', 'nombre_usuario', 'ci', 'estado', 'rol_id', 'entidad_id', 'updated_at']);
        // Verifica si se ha proporcionado una nueva contraseña
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password')); // Encripta la contraseña
        }

        $data['updated_at'] = Carbon::now();

        $usuario->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Usuario actualizado correctamente.',
            'data' => $usuario
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

        //$user = Usuario::where('correo', $request->correo)->first();
        $user = Usuario::with('entidad')->where('correo', $request->correo)->first();

        if ($user->estado === false) {
            return response()->json([
                'status' => false,
                'errors' => 'El usuario está inhabilitado. Contacte al administrador.'
            ], 403);
        }

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
