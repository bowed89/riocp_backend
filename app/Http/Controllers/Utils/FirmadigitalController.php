<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FirmadigitalController extends Controller
{
    /*    public function validarFirmaDigital(Request $request)
    {
        $body = $request->all();
        $response = Http::post('https://validar.firmadigital.bo/rest/validar/', $body);
        return response()->json($response->json(), $response->status());
    } */

    public function validarFirmaDigital(Request $request)
    {
        $body = $request->all();
        $response = Http::timeout(300)
            ->withOptions(['verify' => false])
            ->post('https://validar.firmadigital.bo/rest/validar/', $body);
        return response()->json($response->json(), $response->status());
    }
}
