<?php

namespace App\Http\Controllers\Solicitante;

use App\Events\MenuUpdated;
use App\Http\Controllers\Controller;
use App\Models\InformacionDeuda;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InformacionDeudaController extends Controller
{
    public function storeSolicitudInformacionDeuda(Request $request)
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
            $formularioDuplicado = InformacionDeuda::where('solicitud_id', $solicitud->id)->first();

            if ($formularioDuplicado) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se registro un formulario con una solicitud pendiente.'
                ], 404);
            }

            $formularioRules = [
                'pregunta_1' => 'required|boolean',
                'pregunta_2' => 'required|boolean',
                'pregunta_3' => 'required|boolean',
                'pregunta_4' => 'required|boolean',
                'solicitud_id' => 'required|integer',
            ];

            // Validar los datos del formulario
            $formularioValidator = Validator::make($request->all(), $formularioRules);

            if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $formularioValidator->errors()->all()
                ], 400);
            }

            // Crear Informacion Deuda
            $informacion = new InformacionDeuda($request->input());
            $informacion->solicitud_id = $solicitud->id;
            $informacion->save();

            // Actualizo mi menu pestania para habilitar formulario_2
            $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
            $menu->sigep_anexo = false;
            $menu->formulario_2 = true;
            $menu->registro = false;


            // si la pregunta 1 es si deshabilito pestaña formulario 4
            if ($informacion->pregunta_1) {
                $menu->formulario_4 = false;
            }
            // si la pregunta 2 o pregunta 3 es si deshabilito pestaña formulario 3
            if ($informacion->pregunta_2  || $informacion->pregunta_3) {
                $menu->formulario_3 = false;
            }
       



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

            return response()->json([
                'status' => true,
                'message' => 'Formulario registrado correctamente.',
                'data' => $informacion
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
