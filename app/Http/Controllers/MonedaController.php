<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
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
}
