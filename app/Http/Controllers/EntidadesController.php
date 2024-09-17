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
}
