<?php

namespace App\Http\Services\Solicitante;

use App\Events\MenuUpdated;
use App\Models\InformacionDeuda;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;

class InformacionDeudaService
{
    public function createInformacionDeuda($data)
    {
        $user = Auth::user();

        if ($user) {
            // obtengo el id de la solicitud incompleta del usuario 
            $solicitud = Solicitud::where('usuario_id', $user->id)
                ->where('estado_requisito_id', 1) // 1 es incompleto
                ->first();

            if (!$solicitud) {
                return [
                    'status' => false,
                    'message' => 'No se encontró una solicitud del usuario en proceso. Primero debe completar el FORMULARIO 1 SOLICITUD RIOCP.',
                    'code' => 404
                ];
            }

            // verifico que no exista un formulario creado anteriormente con la misma solicitud_id
            $formularioDuplicado = InformacionDeuda::where('solicitud_id', $solicitud->id)->first();

            if ($formularioDuplicado) {
                return [
                    'status' => false,
                    'message' => 'Ya se registró un formulario con una solicitud pendiente.',
                    'code' => 404
                ];
            }

            // Crear Información Deuda
            $informacion = new InformacionDeuda($data);
            $informacion->solicitud_id = $solicitud->id;
            $informacion->save();

            // Actualizar el menú de pestañas
            $this->updateMenu($solicitud, $informacion);

            return [
                'status' => true,
                'message' => 'Formulario registrado correctamente.',
                'data' => $informacion,
                'code' => 200
            ];
        }

        return [
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.',
            'code' => 403
        ];
    }

    public function getInformacionDeudaById($id)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.',
                'code' => 403
            ];
        }

        $informacionDeuda = InformacionDeuda::where('solicitud_id', $id)->get();

        if (!$informacionDeuda) {
            return [
                'status' => false,
                'message' => 'Solicitud no encontrada',
                'code' => 400,
            ];
        }

        return [
            'status' => true,
            'message' => 'Listado de Información Deuda por id',
            'data' => $informacionDeuda,
            'code' => 200
        ];
    }


    protected function updateMenu($solicitud, $informacion)
    {
        $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
        $menu->sigep_anexo = false;
        $menu->formulario_2 = true;
        // $menu->registro = false;

        // Si la pregunta 1 es true, deshabilitar pestaña formulario 4
        if ($informacion->pregunta_1) {
            $menu->formulario_4 = false;
        }
        // Si la pregunta 2 o pregunta 3 es true, deshabilitar pestaña formulario 3
        if ($informacion->pregunta_2 || $informacion->pregunta_3) {
            $menu->formulario_3 = false;
        }
        // Si la pregunta 1, pregunta 2 y pregunta 3 es false activo pestaña registro
        if (!$informacion->pregunta_1 && !$informacion->pregunta_2 && !$informacion->pregunta_3) {
            $menu->registro = false;
        }

        $menu->save();
        $menu->refresh(); // Actualizar todos los campos

        // Iterar y ajustar el estado `disabled` basado en la clave del array
        $items = config('menu_pestanias');
        foreach ($items as &$item) {
            $key = $item['disabled'];
            if (isset($menu->$key)) {
                $item['disabled'] = $menu->$key;
            }
        }

        // Evento con los datos del menú
        event(new MenuUpdated($items));
    }
}
