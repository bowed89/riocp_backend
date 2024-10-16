<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Periodo;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    public function index()
    {
        $periodos = Periodo::all();

        if ($periodos->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay periodos disponibles.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Listado de periodos.',
            'data' => $periodos,
        ], 200);
    }
}
