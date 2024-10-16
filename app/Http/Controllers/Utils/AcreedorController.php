<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Acreedor;
use Illuminate\Http\Request;

class AcreedorController extends Controller
{
    public function index()
    {
        $acreedores = Acreedor::all();

        if ($acreedores->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay acreedores disponibles.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Listado de acreedores.',
            'data' => $acreedores,
        ], 200);
    }
}
