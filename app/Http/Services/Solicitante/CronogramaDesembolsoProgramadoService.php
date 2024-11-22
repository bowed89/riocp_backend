<?php

namespace App\Http\Services\Solicitante;

use App\Events\MenuUpdated;
use App\Models\CronogramaDesembolsoProgramado;
use App\Models\CronogramaDesembolsoProgramadoMain;
use App\Models\FechaDesembolsoProgramado;
use App\Models\Solicitud;
use App\Models\MenuPestaniasSolicitante;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CronogramaDesembolsoProgramadoService
{
    public function createCronogramaDesembolso($data)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.',
                'code' => 403
            ];
        }

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
        $formularioDuplicado = CronogramaDesembolsoProgramadoMain::where('solicitud_id', $solicitud->id)->first();

        if ($formularioDuplicado) {
            return [
                'status' => false,
                'message' => 'Ya se registró un formulario con una solicitud pendiente.',
                'code' => 404
            ];
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
            return [
                'status' => false,
                'message' => 'No puede completar el formulario CRONOGRAMA DE DESEMBOLSOS PROGRAMADOS Y/O ESTIMADOS porque en su formulario INFORMACIÓN DE DEUDA seleccionó NO en la pregunta 1.',
                'code' => 400
            ];
        }

        // valido si la sumatoria de monto es igual a la sumatoria de saldo A - B
        if ($this->validarSumatorias($data['cronograma_desembolsos']) > 0) {
            return [
                'status' => false,
                'message' => 'Verifique que la sumatoria de los desembolsos programados y/o estimados sean igual al Saldo por desembolso (A-B).',
                'code' => 403
            ];
        }

        // agrego a la tabla de registro desembolsos main
        $registroCronogramaMain = new CronogramaDesembolsoProgramadoMain();
        $registroCronogramaMain->solicitud_id = $solicitud->id;
        $registroCronogramaMain->save();

        foreach ($data['cronograma_desembolsos'] as $bodyDesembolso) {
            $this->registrarDesembolso($bodyDesembolso, $registroCronogramaMain->id);
        }

        // Actualizo mi menu pestania para habilitar formulario_2
        $this->updateMenu($solicitud);

        return [
            'status' => true,
            'message' => 'Se agregó los datos del formulario.',
            'code' => 200
        ];
    }

    protected function validarSumatorias($cronogramaDesembolsos)
    {
        $noCumpleSumatoria = 0;
        foreach ($cronogramaDesembolsos as $bodySuma) {
            if (count($bodySuma['fecha_desembolsos']) > 0 && !($bodySuma['desembolso_desistido'])) {
                // valido si la sumatoria de monto es igual a la sumatoria de saldo A - B
                $sumatoria = 0;
                foreach ($bodySuma['fecha_desembolsos'] as $sumaFechaDesembolso) {
                    $sumatoria += floatval($sumaFechaDesembolso['monto']);
                }
                $tolerancia = 0.01;

                if (abs($sumatoria - floatval($bodySuma['saldo_desembolso_a_b'])) > $tolerancia) {
                    $noCumpleSumatoria++;
                }
            }
        }

        return $noCumpleSumatoria;
    }

    protected function registrarDesembolso($data, $mainId)
    {
        $registroCronograma = new CronogramaDesembolsoProgramado();
        $registroCronograma->fill($data);
        $registroCronograma->cronograma_main_id = $mainId;
        $registroCronograma->save();

        if (count($data['fecha_desembolsos']) > 0) {
            foreach ($data['fecha_desembolsos'] as $fechaDesembolso) {
                FechaDesembolsoProgramado::create([
                    'cronograma_id' => $registroCronograma->id,
                    'fecha' => $fechaDesembolso['fecha'],
                    'monto' => $fechaDesembolso['monto']
                ]);
            }
        }
    }

    public function getCronogramaDesembolsoById($id)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.'
            ];
        }

        $cronogramaMain = CronogramaDesembolsoProgramadoMain::where('solicitud_id', $id)->first();
        if (!$cronogramaMain) {
            return [
                'status' => false,
                'message' => 'No se encontro un cronograma de desembolso con el número de solicitud.',
                'code' => 404
            ];
        }

        $cronogramaDesembolso = CronogramaDesembolsoProgramado::where('cronograma_main_id', $cronogramaMain->id)->get();

        if ($cronogramaDesembolso->count() == 0) {
            return [
                'status' => false,
                'message' => 'No existen cronogramas de desembolso con el número de cronograma principal.',
                'code' => 404
            ];
        }

        foreach ($cronogramaDesembolso as $cd) {
            // Pregunto si en cada cronogramas_desembolso_programado existe array de fechas_desembolsos_programado
            $fechaDesembolso = FechaDesembolsoProgramado::where('cronograma_id', $cd->id)
                ->get();

            $cd->cronograma_desembolsos = $fechaDesembolso;

        }

        return [
            'status' => 200,
            'data' => [
                'status' => true,
                'message' => 'Listado de cronogramas de desembolsos.',
                'data' => $cronogramaDesembolso
            ]
        ];
    }

    protected function updateMenu($solicitud)
    {
        $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
        $menu->formulario_4 = true;

        // si la pestaña formulario_3 esta deshabilitado
        // recien activo pestaña registro
        if($menu->formulario_3) {
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
}
