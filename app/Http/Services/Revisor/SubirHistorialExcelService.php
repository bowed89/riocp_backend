<?php

namespace App\Http\Services\Revisor;

use App\Models\HistorialDocumentoExcel;
use App\Models\Rol;
use App\Models\TipoDocumentoAdjunto;
use Illuminate\Support\Facades\Auth;

class SubirHistorialExcelService
{
    public function crearNuevoHistorial($data)
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.',
                'code' => 401,
            ];
        }

        $filePath = null;
        $nombreTipoDocumento = TipoDocumentoAdjunto::where('id', $data['tipo_documento_id'])->first();

        if (!$nombreTipoDocumento) {
            return [
                'status' => false,
                'message' => 'No existe un Tipo de Documento.',
                'code' => 400,
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
            'status' => true,
            'message' => 'Historial agregado correctamente.',
            'data' => $historial,
            'code' => 201,
        ];
    }

    private function uploadFile($user, $file, $tipo_documento_excel)
    {
        $nombres =  $user->nombre . ' ' . $user->apellido;
        $fechaActual = now()->format('Y-m-d');
        $nombreRol = Rol::where('id', $user->rol_id)->first();
        return $file->store($tipo_documento_excel . '/' . $fechaActual . '/' . $nombreRol->rol . '/' . $nombres, 'public');
    }
}
