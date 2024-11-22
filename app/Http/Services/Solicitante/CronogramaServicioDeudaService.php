<?php

namespace App\Http\Services\Solicitante;

use App\Events\MenuUpdated;
use App\Models\CronogramaServicioDeuda;
use App\Models\CuadroPago;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CronogramaServicioDeudaService
{
    public function createCronogramaServicioDeuda($data)
    {
        $user = Auth::user();

        if ($user) {
            $solicitud = Solicitud::where('usuario_id', $user->id)
                ->where('estado_requisito_id', 1)
                ->first();

            if (!$solicitud) {
                return [
                    'status' => false,
                    'message' => 'No se encontró una solicitud del usuario en proceso. Primero debe completar el FORMULARIO 1 SOLICITUD RIOCP.',
                    'code' => 404
                ];
            }
            // verifico que no exista un formulario creado anteriormente con la misma solicitud_id
            $formularioDuplicado = CronogramaServicioDeuda::where('solicitud_id', $solicitud->id)->first();

            if ($formularioDuplicado) {
                return [
                    'status' => false,
                    'message' => 'Ya se registró un formulario con una solicitud pendiente.',
                    'code' => 404
                ];
            }
            // Verifico si esta habilitado el formulario 3 segun la pregunta 2 y 3 del formulario 2
            $formulariHabilitado = DB::table('informaciones_deuda as a')
                ->join('solicitudes as s', 'a.solicitud_id', '=', 's.id')
                ->where('s.usuario_id', $user->id)
                ->where('s.estado_requisito_id', 1)
                ->select(DB::raw('CASE WHEN a.pregunta_2 = true OR a.pregunta_3 = true THEN true ELSE false END as resultado'))
                ->first();

            if (!$formulariHabilitado->resultado) {
                return [
                    'status' => false,
                    'message' => 'No puede completar el formulario CRONOGRAMA DE SERVICIO DE LA DEUDA, porque en su formulario INFORMACIÓN DE DEUDA seleccionó NO en las preguntas 2 o 3.',
                    'code' => 400
                ];
            }
            // registro datos en la tabla cronograma_servicio_deudas
            $registroDeudas = new CronogramaServicioDeuda($data);
            $registroDeudas->solicitud_id = $solicitud->id;
            $registroDeudas->save();

            foreach ($data['cuadro_pagos'] as $pago) {
                CuadroPago::create([
                    'fecha_vencimiento' => $pago['fecha_vencimiento'],
                    'capital' => $pago['capital'],
                    'interes' => $pago['interes'],
                    'comisiones' => $pago['comisiones'],
                    'total' => $pago['total'],
                    'saldo' => $pago['saldo'],
                    'cronograma_servicio_id' => $registroDeudas->id,
                ]);
            }

            $this->updateMenu($solicitud);

            return [
                'status' => true,
                'message' => 'Se agregó los datos del formulario.',
                'code' => 200
            ];
        }

        return [
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.',
            'code' => 403
        ];
    }

    public function updateMenu($solicitud)
    {
        $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
        $menu->formulario_3 = true;

        // si la pestaña formulario_4 esta deshabilitado
        // recien activo pestaña registro
        if ($menu->formulario_4) {
            $menu->registro = false;
        }

        $menu->save();
        $items = config('menu_pestanias');

        foreach ($items as &$item) {
            $key = $item['disabled'];
            if (isset($menu->$key)) {
                $item['disabled'] = $menu->$key;
            }
        }
        event(new MenuUpdated($items));
    }

    public function getCronogramaCuadrosById($id)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        $cronograma = CronogramaServicioDeuda::where('solicitud_id', $id)->first();

        if (!$cronograma) {
            return [
                'status' => false,
                'message' => 'No se encontro un cronograma con el número de solicitud.',
                'code' => 404
            ];
        }

        $cuadroPagos = CuadroPago::where('cronograma_servicio_id', $cronograma->id)->get();

        if ($cuadroPagos->count() == 0) {
            return [
                'status' => false,
                'message' => 'No existen cuadro de pagos en el número de cronograma.',
                'code' => 404
            ];
        }

        $cronograma->cuadro_pagos = $cuadroPagos;

        return [
            'status' => 200,
            'data' => [
                'status' => true,
                'message' => 'Listado de cronogramas y cuadro de pagos.',
                'data' => $cronograma
            ]
        ];
    }
}
