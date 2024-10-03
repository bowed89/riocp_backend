<?php

namespace App\Http\Controllers;

use App\Models\FormularioCorrespondencia;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FormularioCorrespondenciaController extends Controller
{
    public function index()
    {
        $fomularios = FormularioCorrespondencia::all();

        if ($fomularios->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No hay formularios registrados.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $fomularios,
        ], 200);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $rules = [
                'nombre_completo' => 'required|string|min:5',
                'correo_electronico' => 'required|email|max:255',
                'nombre_entidad' => 'required|string|max:255',
                'cite_documento' => 'string|max:255',
                'referencia' => 'string|max:255',
                'documento' => 'required|file|mimes:pdf|max:10240', // Validar archivo PDF, máximo 10MB
                'ruta_documento' => 'string|max:255',
                'firma_digital' => 'required|boolean',
                'estado' => 'required|boolean',
                'solicitud_id' => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ], 400);
            }


            // Manejo de la subida del archivo
            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $nombres = $user->nombre . ' ' .  $user->apellido;
                $entidad = $user->entidad->denominacion;
                $fechaActual = now()->format('Y-m-d');
                // Guardar el archivo en el almacenamiento local y obtener la ruta
                $filePath = $file->store('correspondencia/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
            }

            $formulario = new FormularioCorrespondencia($request->except('documento'));
            $formulario->ruta_documento = $filePath ?? null; // se guarda la ruta del archivo subido
            $formulario->save();

            return response()->json([
                'status' => true,
                'message' => 'Formulario Creado.',
                'data' => $formulario
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }

    public function storeSolicitudFormulario(Request $request)
    {

        Log::info('Datos recibidos:', $request->all());

        $user = Auth::user();

        if ($user) {
            $solicitudRules = [
                'nro_solicitud' => 'required|string',
                'estado_solicitud_id' => 'required|integer',
            ];

            $solicitudValidator = Validator::make($request->only('nro_solicitud', 'estado_solicitud_id'), $solicitudRules);

            if ($solicitudValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $solicitudValidator->errors()->all()
                ], 400);
            }

            // Crear la solicitud
            $solicitud = new Solicitud($request->only('nro_solicitud', 'estado_solicitud_id', 'estado'));
            $solicitud->usuario_id = $user->id; // Asigna el usuario autenticado
            $solicitud->save();

            $formularioRules = [
                'nombre_completo' => 'required|string|min:5',
                'correo_electronico' => 'required|email|max:255',
                'nombre_entidad' => 'required|string|max:255',
                'cite_documento' => 'string|max:255',
                'referencia' => 'string|max:255',
                'documento' => 'required|file|mimes:pdf|max:10240', // Validar archivo PDF, máximo 10MB
                'ruta_documento' => 'string|max:255',
                'firma_digital' => 'required|boolean',
                'solicitud_id' => 'required|integer'
            ];

            $formularioValidator = Validator::make($request->all(), $formularioRules);

            if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $formularioValidator->errors()->all()
                ], 400);
            }

            // Manejo de la subida del archivo
            $filePath = null;
            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $nombres = $user->nombre . ' ' . $user->apellido;
                $entidad = $user->entidad->denominacion;
                $fechaActual = now()->format('Y-m-d');
                $filePath = $file->store('correspondencia/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
            }

            // Crear el formulario de correspondencia
            $formulario = new FormularioCorrespondencia($request->except('documento'));
            $formulario->solicitud_id = $solicitud->id; 
            $formulario->ruta_documento = $filePath; 
            $formulario->save();

            return response()->json([
                'status' => true,
                'message' => 'Tramite registro correctamente.',
                'data' => $formulario
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }



    public function show($id)
    {
        $formulario = FormularioCorrespondencia::find($id);

        if (!$formulario) {
            return response()->json([
                'status' => false,
                'message' => 'Formulario no encontrado.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $formulario
        ]);
    }

    public function update(Request $request, $id)
    {
        $formulario = FormularioCorrespondencia::find($id);

        if (!$formulario) {
            return response()->json([
                'status' => false,
                'message' => 'Formulario no encontrado.',
            ], 404);
        }

        $rules = [
            'nombre_completo' => 'string|min:5',
            'correo_electronico' => 'email|max:255',
            'nombre_entidad' => 'string|max:255',
            'cite_documento' => 'string|max:255',
            'referencia' => 'string|max:255',
            'documento' => 'required|file|mimes:pdf|max:10240', // Validar archivo PDF, máximo 10MB
            'ruta_documento' => 'string|max:255',
            'firma_digital' => 'required|boolean',
            'solicitud_id' => 'integer'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $formulario->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Formulario actualizado correctamente.',
            'data' => $formulario
        ], 200);
    }

    public function deleteFormulario($id)
    {
        $formulario = FormularioCorrespondencia::find($id);

        if (!$formulario) {
            return response()->json([
                'status' => false,
                'message' => 'Formulario no encontrado.'
            ], 404);
        }

        $formulario->estado = 0;
        $formulario->save();

        return response()->json([
            'status' => true,
            'message' => 'Formulario desactivado correctamente.',
            'data' => $formulario
        ], 200);
    }
}
