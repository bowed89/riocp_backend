<?php

namespace App\Http\Services\Solicitante;

use App\Events\MenuUpdated;
use App\Models\DocumentoAdjunto;
use App\Models\MenuPestaniasSolicitante;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;

class DocumentoAdjuntoService
{
    public function storeDocumentosFormulario1(array $data)
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
            ->where('estado_requisito_id', 1)
            ->first();

        if (!$solicitud) {
            return [
                'status' => false,
                'message' => 'No se encontró una solicitud del usuario en proceso. Debe completar el FORMULARIO 1.'
            ];
        }

        // verifico que no exista un documento creado anteriormente con la misma solicitud_id 
        $anexos_duplicados = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
            ->whereIn('tipo_documento_id', [1, 2])
            ->get();

        if ($anexos_duplicados->isNotEmpty()) {
            foreach ($anexos_duplicados as $anexo) {
                if ($anexo->tipo_documento_id == 1) {
                    return [
                        'status' => false,
                        'message' => 'Ya se adjunto un cronograma de pagos.'
                    ];
                } elseif ($anexo->tipo_documento_id == 2) {
                    return [
                        'status' => false,
                        'message' => 'Ya se adjunto un cronograma de desembolsos.'
                    ];
                }
            }
        }

        // Procesar la subida de archivos y guardar los documentos
        $this->uploadDocument($data['documento_cronograma'], $solicitud, 1, $user, 'cronograma-pagos');
        $this->uploadDocument($data['documento_desembolso'], $solicitud, 2, $user, 'cronograma-desembolsos');

        $this->updateMenu($solicitud, 'form-1');

        return [
            'status' => true,
            'message' => 'Anexo agregado correctamente.'
        ];
    }

    public function storeDocumentoForm2($data)
    {
        return $this->storeDocumentoGenerico($data, 4, 'certificado-riocp-no-vigente');
    }

    public function storeDocumentoForm3($data)
    {
        return $this->storeDocumentoGenerico($data, 3, 'informacion-financiera');
    }

    private function storeDocumentoGenerico($data, int $tipoDocumentoId, string $folder)
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
            ->where('estado_requisito_id', 1)
            ->first();

        if (!$solicitud) {
            return [
                'status' => false,
                'message' => 'No se encontró una solicitud del usuario en proceso.'
            ];
        }

        // verifico que no exista un documento creado anteriormente con la misma solicitud_id
        $anexoDuplicado = DocumentoAdjunto::where('solicitud_id', $solicitud->id)
            ->where('tipo_documento_id', $tipoDocumentoId)
            ->first();

        if ($anexoDuplicado) {
            return [
                'status' => false,
                'message' => $tipoDocumentoId == 4
                    ? 'Ya se adjunto un certificado RIOCP no vigente.'
                    : 'Ya se adjunto una información financiera.'
            ];
        }

        // Procesar subida de archivo
        $this->uploadDocument($data['documento'], $solicitud, $tipoDocumentoId, $user, $folder);

        if($tipoDocumentoId == 3) {
            $this->updateMenu($solicitud, 'form-3');
        }
        
        return [
            'status' => true,
            'message' => 'Documento adjuntado correctamente.'
        ];
    }

    private function uploadDocument($file, $solicitud, $tipoDocumentoId, $user, $folder)
    {
        $filePath = $this->generateFilePath($file, $user, $folder);
        DocumentoAdjunto::create([
            'ruta_documento' => $filePath,
            'solicitud_id' => $solicitud->id,
            'tipo_documento_id' => $tipoDocumentoId
        ]);
    }

    private function generateFilePath($file, $user, $folder)
    {
        $nombres = $user->nombre . ' ' . $user->apellido;
        $entidad = $user->entidad->denominacion;
        $fechaActual = now()->format('Y-m-d');
        return $file->store($folder . '/' . $fechaActual . '/' . $entidad . '/' . $nombres, 'public');
    }

    private function updateMenu($solicitud, string $type)
    {
        $menu = MenuPestaniasSolicitante::where('solicitud_id', $solicitud->id)->first();

        if ($type == 'form-1') {
            $menu->formulario_1_anexo = true;
            $menu->formulario_2 = false;
            
        } elseif ($type == 'form-3') {
            $menu->sigep_anexo = true;
        }
        $menu->save();
        $menu->refresh();
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
