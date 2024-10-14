<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioCorrespondencia extends Model
{
    use HasFactory;
    protected $table = 'formularios_correspondencia';
    protected $fillable = [
        'nombre_completo',
        'correo_electronico',
        'nombre_entidad',
        'cite_documento',
        'referencia',
        'ruta_documento',
        'firma_digital',
        'estado',
        'solicitud_id',
        'tipo_solicitud_id',
    ];

    public function Solicitud()
    {
        return $this->hasOne(Solicitud::class);
    }


}
