<?php

namespace App\Http\Services\Solicitante;

use App\Events\MenuUpdated;
use App\Events\Notificaciones;
use App\Http\Queries\JefeUnidadQuery;
use App\Models\FormularioCorrespondencia;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Seguimientos;
use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class FormularioCorrespondenciaService
{
    public function getAllFormularios()
    {
        $formularios = FormularioCorrespondencia::all();
        if ($formularios->isEmpty()) {
            return [
                'status' => false,
                'message' => 'No hay formularios registrados.'
            ];
        }

        return [
            'status' => true,
            'data' => $formularios
        ];
    }

    public function createFormulario($data)
    {
        $user = Auth::user();

        if (!$user) {
            return ['status' => false, 'message' => 'Usuario no autorizado o sin rol asignado.'];
        }

        // Manejo de la subida del archivo
        if (isset($data['documento'])) {
            $filePath = $this->uploadFile($user, $data['documento']);
            $data['ruta_documento'] = $filePath;
        }

        $formulario = FormularioCorrespondencia::create($data);
        return ['status' => true, 'message' => 'Formulario Creado.', 'data' => $formulario];
    }

    public function createSolicitudFormulario($data)
    {
        $user = Auth::user();

        if (!$user) {
            return ['status' => false, 'message' => 'Usuario no autorizado o sin rol asignado.'];
        }

        // obtengo el id de la solicitud incompleta del usuario 
        $solicitud = Solicitud::where('usuario_id', $user->id)
            ->where('estado_requisito_id', 1) // 1 es incompleto
            ->first();

        if (!$solicitud) {
            return [
                'status' => false,
                'message' => 'No se encontró una solicitud del usuario en proceso. Primero debe completar el FORMULARIO 1 SOLICITUD RIOCP.'
            ];
        }
        
        // verifico que no exista un formulario creado anteriormente con la misma solicitud_id
        $formularioDuplicado = FormularioCorrespondencia::where('solicitud_id', $solicitud->id)->first();
        if ($formularioDuplicado) {
            return ['status' => false, 'message' => 'Ya se registró un formulario con una solicitud pendiente.'];
        }
        // verifico que no exista un seguimiento creado anteriormente con la misma solicitud_id
        $seguimientoDuplicado = Seguimientos::where('solicitud_id', $solicitud->id)->first();

        if ($seguimientoDuplicado) {
            return ['status' => false, 'message' => 'Ya se registró un seguimiento con una solicitud pendiente.'];
        }

        $filePath = null;
        // obtengo el usuario destino de jefe de unidad
        $usuarioDestino = Usuario::where('rol_id', 2)->first();
        $this->createSeguimiento($user, $usuarioDestino, $solicitud->id);

        // Manejo de la subida del archivo
        if (isset($data['documento'])) {
            $filePath = $this->uploadFile($user, $data['documento']);
            $data['ruta_documento'] = $filePath;
        }

        // Crear el formulario de correspondencia
        //$formulario = new FormularioCorrespondencia($data->except('documento'));
        $formulario = new FormularioCorrespondencia();

        $formulario->solicitud_id = $solicitud->id;
        $formulario->ruta_documento = $filePath;
        $formulario->save();

        $solicitud->update(['nro_solicitud' => $this->generateNroSolicitud()]);

        $this->updateMenu($$solicitud);

        // Event para notificaciones de nuevos tramites
        $userJefeUnidad = Usuario::where('rol_id', 2)->first();
        $this->emitNotificacion($userJefeUnidad);

        return ['status' => true, 'message' => 'Formulario registrado correctamente.', 'data' => $formulario];
    }

    private function uploadFile($user, $file)
    {
        $nombres = $user->nombre . ' ' . $user->apellido;
        $entidad = $user->entidad->denominacion;
        $fechaActual = now()->format('Y-m-d');
        return $file->store('correspondencia/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
    }

    private function createSeguimiento($user, $usuarioDestino, $solicitudId)
    {
        $seguimiento = new Seguimientos();
        $seguimiento->usuario_origen_id = $user->id;
        $seguimiento->usuario_destino_id = $usuarioDestino->id;
        $seguimiento->observacion = 'DERIVACIÓN POR DEFECTO A JEFE DE UNIDAD.';
        $seguimiento->solicitud_id = $solicitudId;
        $seguimiento->save();

        return $seguimiento;
    }

    public function updateMenu($solicitud)
    {
        $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();
        $menu->formulario_3 = true;
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

    private function emitNotificacion($user)
    {
        $resultados = JefeUnidadQuery::getJefeUnidadList($user);
        $count = array_reduce($resultados, function ($carry, $res) {
            return $res['estado'] === 'SIN DERIVAR' ? $carry + 1 : $carry;
        }, 0);

        event(new Notificaciones($count));
    }

    private function generateNroSolicitud()
    {
        // ANTERIOR
        // 1000 1 22 11 1434
        // 1000122111434-1

        // EJEMPLO FINAL
        // 1000 1 22 11 1434

        $fecha = date("dmy");
        $numerosAleatorios = mt_rand(100, 999);
        return $fecha . '' . $numerosAleatorios;
    }

    public function getFormularioById($id)
    {
        $formulario = FormularioCorrespondencia::find($id);
        if (!$formulario) {
            return ['status' => false, 'message' => 'Formulario no encontrado.'];
        }

        return ['status' => true, 'data' => $formulario];
    }

    public function updateFormulario($id, $data)
    {
        $formulario = FormularioCorrespondencia::find($id);
        if (!$formulario) {
            return ['status' => false, 'message' => 'Formulario no encontrado.'];
        }

        $formulario->update($data);

        return ['status' => true, 'message' => 'Formulario actualizado correctamente.', 'data' => $formulario];
    }

    public function deactivateFormulario($id)
    {
        $formulario = FormularioCorrespondencia::find($id);
        if (!$formulario) {
            return ['status' => false, 'message' => 'Formulario no encontrado.'];
        }

        $formulario->update(['estado' => 0]);
        return ['status' => true, 'message' => 'Formulario desactivado correctamente.', 'data' => $formulario];
    }
}
