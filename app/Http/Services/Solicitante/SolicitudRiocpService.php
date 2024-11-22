<?php

namespace App\Http\Services\Solicitante;

use App\Events\MenuUpdated;
use App\Models\ContactoSubsanar;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use App\Models\SolicitudRiocp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    /* POR ID */
    public function getSolicitudesById($id)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.',
                'code' => 403
            ];
        }

        $solicitud = DB::table('solicitudes_riocp as sr')
            ->join('contactos_subsanar as cs', 'sr.contacto_id', '=', 'cs.id')
            ->join('entidades as e', 'sr.entidad_id', '=', 'e.id')
            ->where('sr.solicitud_id', $id)
            ->select(
                'sr.id',
                'sr.monto_total',
                'sr.plazo',
                'sr.interes_anual',
                'sr.comision_concepto',
                'sr.comision_tasa',
                'sr.declaracion_jurada',
                'sr.periodo_gracia',
                'sr.objeto_operacion_credito',
                'sr.firma_digital',
                'sr.ruta_documento',
                'sr.solicitud_id',
                'sr.acreedor_id',
                'sr.moneda_id',
                'sr.entidad_id',
                'sr.identificador_id',
                'sr.periodo_id',
                'sr.contacto_id',
                'sr.estado',
                'sr.created_at',
                'sr.updated_at',
                'cs.nombre_completo',
                'cs.cargo',
                'cs.correo_electronico',
                'cs.telefono',
                'e.denominacion',
                'e.entidad_id'
            )
            ->get();

        if (!$solicitud) {
            return [
                'status' => false,
                'message' => 'Solicitud no encontrada',
                'code' => 400,
            ];
        }

        return [
            'status' => true,
            'message' => 'Listado de Solicitud RICOP por id',
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
