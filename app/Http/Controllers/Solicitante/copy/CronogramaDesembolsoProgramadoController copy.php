<?php

namespace App\Http\Controllers\Solicitante;

use App\Events\MenuUpdated;
use App\Http\Controllers\Controller;
use App\Models\CronogramaDesembolsoProgramado;
use App\Models\CronogramaDesembolsoProgramadoMain;
use App\Models\FechaDesembolsoProgramado;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CronogramaDesembolsoProgramadoController extends Controller
{
    public function storeCronogramaDesembolso(Request $request)
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
            $formularioDuplicado = CronogramaDesembolsoProgramadoMain::where('solicitud_id', $solicitud->id)->first();

            if ($formularioDuplicado) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ya se registro un formulario con una solicitud pendiente.'
                ], 404);
            }

            // Verifico si esta habilitado el formulario 4 segun la pregunta 1 del formulario 2
            $formulariHabilitado = DB::table('informaciones_deuda as a')
                ->join('solicitudes as s', 'a.solicitud_id', '=', 's.id')
                ->where('s.usuario_id', $user->id)
                ->where('s.estado_requisito_id', 1)
                ->select(DB::raw('CASE WHEN a.pregunta_1 = true THEN true ELSE false END as resultado'))
                ->first();

            // Si en el formulario 2 la pregunta_1 es true, puedo registrar en el formulario 4
            if (!$formulariHabilitado || !$formulariHabilitado->resultado) {
                return response()->json([
                    'status' => false,
                    'message' => 'No puede completar el formulario CRONOGRAMA DE DESEMBOLSOS PROGRAMADOS Y/O ESTIMADOS porque en su formulario INFORMACIÓN DE DEUDA seleccionó NO en la pregunta 1.'
                ], 400);
            }

            $formularioRules = [
                // Reglas para cronograma_desembolsos
                'cronograma_desembolsos' => 'required|array',
                'cronograma_desembolsos.*.objeto_deuda' => 'required|string',
                'cronograma_desembolsos.*.monto_contratado_a' => 'required|numeric',
                'cronograma_desembolsos.*.monto_desembolsado_b' => 'required|numeric',
                'cronograma_desembolsos.*.saldo_desembolso_a_b' => 'required|numeric',
                'cronograma_desembolsos.*.desembolso_desistido' => 'required|boolean',
                'cronograma_desembolsos.*.acreedor_id' => 'required|integer',

                // Reglas para fecha_desembolsos dentro de cronograma_desembolsos
                'cronograma_desembolsos.*.fecha_desembolsos' => 'array',
                'cronograma_desembolsos.*.fecha_desembolsos.*.fecha' => 'date',
                'cronograma_desembolsos.*.fecha_desembolsos.*.monto' => 'numeric'
            ];

            $formularioValidator = Validator::make($request->all(), $formularioRules);

            if ($formularioValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $formularioValidator->errors()->all()
                ], 400);
            }

            // valido si la sumatoria de monto es igual a la sumatoria de saldo A - B
            $noCumpleSumatoria = 0;
            foreach ($request['cronograma_desembolsos'] as $bodySuma) {
                if (count($bodySuma['fecha_desembolsos']) > 0 && !($bodySuma['desembolso_desistido'])) {
                    // valido si la sumatoria de monto es igual a la sumatoria de saldo A - B
                    $sumatoria = 0;
                    foreach ($bodySuma['fecha_desembolsos'] as $sumaFechaDesembolso) {
                        $sumatoria += floatval($sumaFechaDesembolso['monto']);
                    }

                    // Tolerancia de comparación para evitar problemas con decimales
                    $tolerancia = 0.01;
                    // Me fijo q la sumatoria con decimales no sean iguales
                    if (abs($sumatoria - floatval($bodySuma['saldo_desembolso_a_b'])) > $tolerancia) {
                        $noCumpleSumatoria += 1;
                    }
                }
            }

            if ($noCumpleSumatoria > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Verifique que la sumatoria de los desembolsos programados y/o estimados sean igual al Saldo por desembolso(A-B)',
                ], 403);

            } else {
                // agrego a la tabla de registro desembolsos main
                $registroCronogramaMain = new CronogramaDesembolsoProgramadoMain();
                $registroCronogramaMain->solicitud_id = $solicitud->id;
                $registroCronogramaMain->save();

                foreach ($request['cronograma_desembolsos'] as $bodyDesembolso) {

                    $registroCronograma = new CronogramaDesembolsoProgramado();
                    $registroCronograma->objeto_deuda = $bodyDesembolso['objeto_deuda'];
                    $registroCronograma->monto_contratado_a = $bodyDesembolso['monto_contratado_a'];
                    $registroCronograma->monto_desembolsado_b = $bodyDesembolso['monto_desembolsado_b'];
                    $registroCronograma->saldo_desembolso_a_b = $bodyDesembolso['saldo_desembolso_a_b'];
                    $registroCronograma->desembolso_desistido = $bodyDesembolso['desembolso_desistido'];
                    $registroCronograma->acreedor_id = $bodyDesembolso['acreedor_id'];
                    $registroCronograma->cronograma_main_id = $registroCronogramaMain->id;
                    $registroCronograma->save();

                    if (count($bodyDesembolso['fecha_desembolsos']) > 0) {
                        foreach ($bodyDesembolso['fecha_desembolsos'] as $bodyFecha) {
                            $registroFecha = new FechaDesembolsoProgramado();
                            $registroFecha->fecha = $bodyFecha['fecha'];
                            $registroFecha->monto = $bodyFecha['monto'];
                            $registroFecha->cronograma_id = $registroCronograma->id;
                            $registroFecha->save();
                        }
                    }
                }

                // Actualizo mi menu pestania para habilitar formulario_2
                $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
                $menu->formulario_4 = true;
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
                    'message' => 'Registro de formulario correctamente.',
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Usuario no autorizado o sin rol asignado.'
        ], 403);
    }
}
