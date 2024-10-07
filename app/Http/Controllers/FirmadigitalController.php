<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FirmadigitalController extends Controller
{
    public function validarFirmaDigital(Request $request)
    {
        $body = $request->all();
        $response = Http::post('https://validar.firmadigital.bo/rest/validar/', $body);
        return response()->json($response->json(), $response->status());
    }
}
