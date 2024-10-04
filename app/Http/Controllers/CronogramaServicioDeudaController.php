<?php

namespace App\Http\Controllers;

use App\Models\CronogramaServicioDeuda;
use App\Models\CuadroPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CronogramaServicioDeudaController extends Controller
{

    public function storeSolicitudCronogramaServicioDeuda(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Verifico si esta habilitado el formulario 3
            $verifico = DB::table('informaciones_deuda as a')
                ->join('solicitudes as s', 'a.solicitud_id', '=', 's.id')
                ->where('s.usuario_id', $user->id)
                ->where('s.estado_requisito_id', 1)
                ->select(DB::raw('CASE WHEN a.pregunta_2 = true OR a.pregunta_3 = true THEN true ELSE false END as resultado'))
                ->get();

            // Si tiene mas de dos tramites pendientes '1'
            if (count($verifico) > 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Existen más de 2 trámites abiertos. Complete o elimine sus trámites anteriores.',
                    'data' => $verifico
                ], 400);
            }

            // Si en el formulario 2 la pregunta_2 o pregunta_3 es true, se habilita el formulario 3
            if ($verifico[0]->resultado) {
                //convierto mis numero de formato 100.098,00 a decimales => 100098.00
                $requestData = $request->all();
                $convertedData = convertirNumerosEnArray($requestData);

                return response()->json([
                    'status' => true,
                    'data' => $convertedData
                ], 400);


                $formularioRules = [
                    'acreedor_id' => 'required|integer',
                    'objeto_deuda' => 'required|string',
                    'moneda_id' => 'required|integer',
                    'total_capital' => 'required|string',
                    'total_interes' => 'required|string',
                    'total_comisiones' => 'required|string',
                    'total_sum' => 'required|string',
                    // cuadros_pagos
                    // cuadros_pagos: valido un array de objetos
                    'cuadro_pagos' => 'required|array',
                    'cuadro_pagos.*.fecha_vencimiento' => 'required|date',
                    'cuadro_pagos.*.capital' => 'required|numeric',
                    'cuadro_pagos.*.interes' => 'required|numeric',
                    'cuadro_pagos.*.comisiones' => 'required|numeric',
                    'cuadro_pagos.*.total' => 'required|numeric',
                    'cuadro_pagos.*.saldo' => 'required|numeric',
                ];

                $formularioValidator = Validator::make($request->all(), $formularioRules);

                if ($formularioValidator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $formularioValidator->errors()->all()
                    ], 400);
                }

                // Primero registrar cronograma_servicio_deudas
                $registroDeudas = new CronogramaServicioDeuda();
                $registroDeudas = $request->input('acreedor_id');
                $registroDeudas = $request->input('objeto_deuda');
                $registroDeudas = $request->input('moneda_id');
                $registroDeudas->save();

                // Agrego la tabla cuadro_pago
                $cuadroPago = new CuadroPago();



                return response()->json([
                    'status' => true,
                    'message' => 'un tramite y es true.',
                    'data' => $verifico[0]->resultado
                ], 400);
            }


            return response()->json([
                'status' => false,
                'message' => 'No puede completar el formulario CRONOGRAMA DE SERVICIO DE LA DEUDA, porque en su formulario INFORMACIÓN DE DEUDA selecciono NO en las preguntas 2 o 3.',
            ], 400);






            /*   if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $formularioValidator->errors()->all()
                ], 400);
            }
             */
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
