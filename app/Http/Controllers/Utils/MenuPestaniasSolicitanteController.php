<?php

namespace App\Http\Controllers\Utils;

use App\Events\MenuUpdated;
use App\Http\Controllers\Controller;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;

class MenuPestaniasSolicitanteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = config('menu_pestanias');

        if ($user) {
            // obtengo el id de la solicitud incompleta del usuario 
            $solicitud = Solicitud::where('usuario_id', $user->id)
                ->where('estado_requisito_id', 1) // 1 es incompleto
                ->first();

            if ($solicitud) {
                $menuConSolicitud =  MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
                // Iterar y ajustar el estado `disabled` basado en la clave del array
                foreach ($items as &$item) {
                    $key = $item['disabled'];
                    if (isset($menuConSolicitud->$key)) {
                        $item['disabled'] = $menuConSolicitud->$key;
                    }
                }

                // websocket
                event(new MenuUpdated($items));

                return response()->json([
                    'status' => false,
                    'message' => 'Listado de menu pestañas activadas.',
                    'data' => $items,
                ], 200);
            }

            // pregunto si existen registros vacios antes para borrar
            MenuPestaniasSolicitante::whereNull('solicitud_id')->delete();

            // Creo un nuevo registro de menu pestaña
            $menu = new MenuPestaniasSolicitante();
            $menu->save();
            $menu->refresh(); // devuelve todos los campos no solo created_at y updated_at

            // Iterar y ajustar el estado `disabled` basado en la clave del array
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
                'message' => 'Listado de menu pestañas activadas.',
                'data' => $items,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
