<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Entidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entidades = Entidad::all();

        if ($entidades->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay listado de Entidades.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Listado de Entidades.',
            'data' => $entidades,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Entidad $entidades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entidad $entidades)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entidad $entidades)
    {
        //
    }

    public function getEntidadByUser()
    {
        $user = Auth::user();
        
        if ($user) {
            if ($user->rol_id == 1) { // rol solicitante
                $entidades = Entidad::select(
                    'entidades.denominacion',
                    'entidades.entidad_id as num_entidad',
                    'usuarios.entidad_id',
                    'usuarios.nombre',
                    'usuarios.apellido',
                    'usuarios.correo',
                    'usuarios.nombre_usuario'
                )
                    ->join('usuarios', 'usuarios.entidad_id', '=', 'entidades.id')
                    ->where('usuarios.id', $user->id)
                    ->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Listado de usario por rol solicitante.',
                    'data' => $entidades,
                ], 200);

            } else { // sino es rol solicitante...
                return response()->json([
                    'status' => true,
                    'message' => 'Rol de usuario.',
                    'data' => [$user],
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado.'
        ], 403);

        
    }
    
}
