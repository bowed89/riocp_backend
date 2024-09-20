<?php

namespace App\Http\Controllers;

use App\Models\Entidad;
use App\Models\entidades;
use Illuminate\Http\Request;

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

    public function getEntidadByUser($id)
    {

        $entidades = Entidad::select(
            'entidades.denominacion',
            'usuarios.entidad_id',
            'usuarios.nombre',
            'usuarios.apellido',
            'usuarios.correo',
            'usuarios.nombre_usuario'
        )
            ->join('usuarios', 'usuarios.entidad_id', '=', 'entidades.id')
            ->where('usuarios.id', $id)
            ->get();

        if ($entidades->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No existe rol Solicitante.'
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Listado de usario por rol solicitante.',
            'data' => $entidades,
        ], 200);
    }
}
