<?php


namespace App\Http\Services\Utils;

use App\Events\Notificaciones;
use App\Http\Queries\JefeUnidadQuery;
use Illuminate\Support\Facades\Auth;

class NotificacionesService
{
    public function Notificacion()
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuario no autorizado o sin rol asignado.',
                'data' => $user,
                'code' => 401
            ];
        }

        $this->emitNotificacion($user);

        return [
            'status' => true,
            'message' => 'Notificación enviada correctamente.',
            'code' => 200
        ];
    }


    private function emitNotificacion($user)
    {
        $resultados = JefeUnidadQuery::getJefeUnidadList($user);
        $count = 0;

        if (count($resultados) > 0) {
            foreach ($resultados as $res) {
                if ($res['estado'] == 'SIN DERIVAR') {
                    $count += 1;
                }
            }

            event(new Notificaciones($count));
        }
    }
}
