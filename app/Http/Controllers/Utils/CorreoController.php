<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Mail\NuevoRegistroMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CorreoController extends Controller
{
    public function sendEmail(Request $request)
    {
        $email = $request->email;
        Mail::to($email)->send(new NuevoRegistroMail($request));
        return response()->json(['message' => 'Registro creado y correo enviado.']);
    }
}
