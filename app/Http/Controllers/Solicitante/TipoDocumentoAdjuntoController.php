<?php

namespace App\Http\Controllers\Solicitante;

use App\Http\Controllers\Controller;
use App\Models\TipoDocumentoAdjunto;
use Illuminate\Http\Request;

class TipoDocumentoAdjuntoController extends Controller
{
    public function index()
    {
        $tipos = TipoDocumentoAdjunto::all();

        if ($tipos->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay tipos de documento disponibles.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $tipos,
        ], 200);
    }
}
