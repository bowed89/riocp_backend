<?php

namespace App\Http\Controllers\Solicitante;

use App\Events\MenuUpdated;
use App\Http\Controllers\Controller;
use App\Models\CronogramaServicioDeuda;
use App\Models\CuadroPago;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CronogramaServicioDeudaController extends Controller
{

    public function storeCronogramaServicioDeuda(Request $request)
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
            $formularioDuplicado = CronogramaServicioDeuda::where('solicitud_id', $solicitud->id)->first();

            if ($formularioDuplicado) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se registro un formulario con una solicitud pendiente.'
                ], 404);
            }

            // Verifico si esta habilitado el formulario 3 segun la pregunta 2 y 3 del formulario 2
            $formulariHabilitado = DB::table('informaciones_deuda as a')
                ->join('solicitudes as s', 'a.solicitud_id', '=', 's.id')
                ->where('s.usuario_id', $user->id)
                ->where('s.estado_requisito_id', 1)
                ->select(DB::raw('CASE WHEN a.pregunta_2 = true OR a.pregunta_3 = true THEN true ELSE false END as resultado'))
                ->get();

            // Si en el formulario 2 la pregunta_2 o pregunta_3 es true, puedo registrar en el formulario 3
            if ($formulariHabilitado[0]->resultado) {
                //convierto mis numero de formato 100.098,00 a decimales => 100098.00
                $requestData = $request->all();
                $formularioRules = [
                    'acreedor_id' => 'required|integer',
                    'objeto_deuda' => 'required|string',
                    'moneda_id' => 'required|integer',
                    'total_capital' => 'required|numeric',
                    'total_interes' => 'required|numeric',
                    'total_comisiones' => 'required|numeric',
                    'total_sum' => 'required|numeric',

                    // cuadros_pagos: valido un array de objetos
                    'cuadro_pagos' => 'required|array',
                    'cuadro_pagos.*.fecha_vencimiento' => 'required|date',
                    'cuadro_pagos.*.capital' => 'required|numeric',
                    'cuadro_pagos.*.interes' => 'required|numeric',
                    'cuadro_pagos.*.comisiones' => 'required|numeric',
                    'cuadro_pagos.*.total' => 'required|numeric',
                    'cuadro_pagos.*.saldo' => 'required|numeric',
                ];

                //$formularioValidator = Validator::make($convertedData, $formularioRules);
                $formularioValidator = Validator::make($request->all(), $formularioRules);

                if ($formularioValidator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $formularioValidator->errors()->all()
                    ], 400);
                }

                // registro datos en la tabla cronograma_servicio_deudas
                $registroDeudas = new CronogramaServicioDeuda();

                $registroDeudas->solicitud_id = $solicitud->id;
                $registroDeudas->acreedor_id = $request->input('acreedor_id');
                $registroDeudas->objeto_deuda = $request->input('objeto_deuda');
                $registroDeudas->moneda_id = $request->input('moneda_id');

                $registroDeudas->total_capital =  $request->input('total_capital');
                $registroDeudas->total_interes =  $request->input('total_interes');
                $registroDeudas->total_comisiones =  $request->input('total_comisiones');
                $registroDeudas->total_sum =  $request->input('total_sum');
                $registroDeudas->save();

                // registro el array cuadros_pago en su tabla 
                $idRegistroDeuda = $registroDeudas->id;

                foreach ($request['cuadro_pagos'] as $pago) {
                    $cuadrosPago = new CuadroPago();
                    $cuadrosPago->fecha_vencimiento = $pago['fecha_vencimiento'];
                    $cuadrosPago->capital = $pago['capital'];
                    $cuadrosPago->interes = $pago['interes'];
                    $cuadrosPago->comisiones = $pago['comisiones'];
                    $cuadrosPago->total = $pago['total'];
                    $cuadrosPago->saldo = $pago['saldo'];
                    $cuadrosPago->cronograma_servicio_id = $idRegistroDeuda;
                    $cuadrosPago->save();
                }

                // Actualizo mi menu pestania 
                $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
                $menu->formulario_3 = true;
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
                    'message' => 'Se agrego los datos del formulario.',
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'No puede completar el formulario CRONOGRAMA DE SERVICIO DE LA DEUDA, porque en su formulario INFORMACIÓN DE DEUDA selecciono NO en las preguntas 2 o 3.',
            ], 400);
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
