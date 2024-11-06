<?php

namespace App\Http\Controllers\Solicitante;

use App\Events\MenuUpdated;
use App\Events\Notificaciones;
use App\Http\Controllers\Controller;
use App\Http\Queries\JefeUnidadQuery;
use App\Models\FormularioCorrespondencia;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Seguimientos;
use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'nombre_completo' => 'required|string|max:255',
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
        $user = Auth::user();

        if ($user) {
            // obtengo el id de la solicitud incompleta del usuario 
            $solicitud = Solicitud::where('usuario_id', $user->id)
                ->where('estado_requisito_id', 1) // 1 es incompleto
                ->first();

            if (!$solicitud) {
                return response()->json([
                    'status' => false,
                    'message' => 'No se encontró una solicitud del usuario en proceso. Primero debe completar el FORMULARIO 1 SOLICITUD RIOCP.'
                ], 404);
            }

            // verifico que no exista un formulario creado anteriormente con la misma solicitud_id
            $formularioDuplicado = FormularioCorrespondencia::where('solicitud_id', $solicitud->id)->first();

            if ($formularioDuplicado) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se registro un formulario con una solicitud pendiente.'
                ], 404);
            }

            $formularioRules = [
                'nombre_completo' => 'required|string|max:255',
                'correo_electronico' => 'required|email|max:255',
                'nombre_entidad' => 'required|string|max:255',
                'cite_documento' => 'string|max:255',
                'referencia' => 'string|max:255',
                'documento' => 'required|file|mimes:pdf|max:10240', // Validar archivo PDF, máximo 10MB
                'firma_digital' => 'required|boolean',
            ];

            $formularioValidator = Validator::make($request->all(), $formularioRules);

            if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $formularioValidator->errors()->all()
                ], 400);
            }

            // verifico que no exista un seguimiento creado anteriormente con la misma solicitud_id
            $seguimientoDuplicado = Seguimientos::where('solicitud_id', $solicitud->id)->first();

            if ($seguimientoDuplicado) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se registro un seguimiento con una solicitud pendiente.'
                ], 404);
            }

            // obtengo el usuario destino de jefe de unidad
            $usuarioDestino = Usuario::where('rol_id', 2)->first();

            // Realizar registro de seguimiento
            $seguimiento = new Seguimientos();
            $seguimiento->usuario_origen_id = $user->id;
            $seguimiento->usuario_destino_id = $usuarioDestino->id;
            $seguimiento->observacion = 'DERIVACIÓN POR DEFECTO A JEFE DE UNIDAD.';
            $seguimiento->solicitud_id = $solicitud->id;
            $seguimiento->save();

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

            // crear nro_solicitud fecha y num aleatorio y actualizar solicitud
            $fecha = date("dmy");
            $numerosAleatorios = mt_rand(100, 999);
            $numeroGenerado = $fecha . '' . $numerosAleatorios;
            $solicitud->nro_solicitud = $numeroGenerado;
            $solicitud->save();

            // Actualizo mi menu pestania 
            $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
            $menu->formulario_1 = true;
            $menu->formulario_2 = true;
            $menu->formulario_3 = true;
            $menu->formulario_4 = true;
            $menu->formulario_1_anexo = true;
            $menu->sigep_anexo = true;
            $menu->registro = true;

            $menu->save();
            $menu->refresh(); // devuelve todos los campos no solo created_at y updated_at
            // Iterar y ajustar el estado `disabled` basado en la clave del array
            $items = config('menu_pestanias');

            foreach ($items as &$item) {
                $key = $item['disabled'];
                if (isset($menu->$key)) {
                    $item['disabled'] = $menu->$key;
                }
            }

            // evento con los datos del menu
            event(new MenuUpdated($items));

            // Event para notificaciones de nuevos tramites
            $userJefeUnidad = Usuario::where('rol_id', 2)->first();
            $this->emitNotificacion($userJefeUnidad);

            return response()->json([
                'status' => true,
                'message' => 'Formulario registrado correctamente.',
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
            'nombre_completo' => 'string|max:255',
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

    private function emitNotificacion($user)
    {
        $resultados = JefeUnidadQuery::getJefeUnidadList($user);
        $count = 0;
        foreach ($resultados as $res) {
            if ($res['estado'] == 'SIN DERIVAR') {
                $count += 1;
            }
        }
        event(new Notificaciones($count));
    }

}
