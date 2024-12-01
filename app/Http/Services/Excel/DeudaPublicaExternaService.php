<?php

namespace App\Http\Services\Excel;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DeudaPublicaExternaImport;
use App\Models\HistorialDocumentoExcel;
use App\Models\Rol;
use App\Models\TipoDocumentoAdjunto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeudaPublicaExternaService
{
    public function importarArchivo($data)
    {

        try {
            Log::debug('data==>' . $data['file']);
            // cargo a mi BD
            Excel::import(new DeudaPublicaExternaImport, $data['file']);
            //Excel::import(new CronogramaDeudaPublicaExternaImport, $data['file']);

            // luego almaceno a mi tabla historial y guardo en mi ruta
            $user = Auth::user();
            if (!$user) {
                return [
                    'status' => 400,
                    'message' => 'Usuario no autorizado o sin rol asignado.'
                ];
            }

            $filePath = null;
            $nombreTipoDocumento = TipoDocumentoAdjunto::where('id', $data['tipo_documento_id'])->first();

            if (!$nombreTipoDocumento) {
                return [
                    'status' => 400,
                    'message' => 'No existe un Tipo de Documento.',
                ];
            }

            if (isset($data['file'])) {
                $filePath = $this->uploadFile($user, $data['file'], $nombreTipoDocumento->tipo);
            }

            // Crear el historial documento excel
            $historial = new HistorialDocumentoExcel();
            $historial->usuario_id = $user->id;
            $historial->tipo_documento_id = $data['tipo_documento_id'];
            $historial->ruta_documento = $filePath;
            $historial->save();

            return [
                'status' => 200,
                'message' => 'Archivo importado correctamente!',
            ];
        } catch (\Exception $e) {
            Log::error('Error al importar archivo: ' . $e->getMessage());
            return [
                'status' => 500,
                'message' => 'Ocurrió un error al importar el archivo.',
            ];
        }
    }

    private function uploadFile($user, $file, $tipo_documento_excel)
    {
        $nombres =  $user->nombre . ' ' . $user->apellido;
        $fechaActual = now()->format('Y-m-d');
        $nombreRol = Rol::where('id', $user->rol_id)->first();

        $nombre_archivo =  $fechaActual . '_' . $user->nombre . '_' . $user->apellido . '.'  . $file->getClientOriginalExtension();

        $ruta_archivo = $tipo_documento_excel . '/' . $fechaActual . '/' . $nombreRol->rol . '/' . $nombres;

        return $file->storeAs($ruta_archivo, $nombre_archivo, 'public');
    }
}
