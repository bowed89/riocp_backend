<?php

namespace App\Http\Services\Solicitante;

use App\Events\MenuUpdated;
use App\Models\ContactoSubsanar;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use App\Models\SolicitudRiocp;
use Illuminate\Support\Facades\Auth;

class SolicitudRiocpService
{
    public function getAllSolicitudes()
    {
        $solicitud = SolicitudRiocp::all();

        return [
            'status' => !$solicitud->isEmpty(),
            'message' => $solicitud->isEmpty() ? 'No hay solicitudes registrados.' : '',
            'data' => $solicitud,
            'code' => 200
        ];
    }

    public function createSolicitudRiocp($data)
    {
        $user = Auth::user();

        // Verificar si el usuario tiene solicitudes incompletas
        $solicitudFaltante = Solicitud::where('usuario_id', $user->id)
            ->where('estado_requisito_id', 1)
            ->get();

        if ($solicitudFaltante->isEmpty()) {
            // Validar que la firma digital sea TRUE
            if (!$data['firma_digital']) {
                return [
                    'status' => false,
                    'message' => 'El formulario PDF no tiene firma digital.',
                    'code' => 400,
                ];
            }

            // Crear la solicitud
            $solicitud = new Solicitud();
            $solicitud->usuario_id = $user->id; // Asigna el usuario autenticado
            $solicitud->save();

            // Crear Contacto Subsanar
            $contacto = ContactoSubsanar::create([
                'nombre_completo' => $data['nombre_completo'],
                'correo_electronico' => $data['correo_electronico'],
                'cargo' => $data['cargo'],
                'telefono' => $data['telefono'],
            ]);

            // Manejo de la subida del archivo
            $filePath = $this->handleFileUpload($data['documento'], $user);

            // Crear la solicitud RIOCP
            $solicitudRiocp = new SolicitudRiocp($data);
            $solicitudRiocp->ruta_documento = $filePath;
            $solicitudRiocp->solicitud_id = $solicitud->id;
            $solicitudRiocp->contacto_id = $contacto->id;
            $solicitudRiocp->save();

            // Actualizar el menú de pestañas
            $this->updateMenu($solicitudRiocp);

            return [
                'status' => true,
                'message' => 'Formulario registrado correctamente.',
                'data' => $solicitudRiocp,
                'code' => 200,
            ];
        }

        return [
            'status' => false,
            'message' => 'Tiene una solicitud sin completar. Complete o cancele su solicitud anterior.',
            'data' => $solicitudFaltante,
            'code' => 400,
        ];
    }

    protected function handleFileUpload($file, $user)
    {
        $nombres = $user->nombre . ' ' . $user->apellido;
        $entidad = $user->entidad->denominacion;
        $fechaActual = now()->format('Y-m-d');
        return $file->store('formularios-riocp/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
    }

    protected function updateMenu($solicitudRiocp)
    {
        // Actualizo mi menu pestania para habilitar formulario_2
        $menu = MenuPestaniasSolicitante::where('solicitud_id', null)->first();
        $menu->solicitud_id = $solicitudRiocp->solicitud_id;
        $menu->formulario_1 = true;
        $menu->formulario_1_anexo = false;
        $menu->formulario_2 = false;
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

        // Evento con los datos del menú
        event(new MenuUpdated($items));
    }
}
