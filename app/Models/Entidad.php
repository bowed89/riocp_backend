<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    use HasFactory;
    protected $table = 'entidades';

    protected $fillable = [
        'entidad_id',
        'par_tipo_institucion',
        'par_departamento',
        'denominacion',
        'sigla'
    ];

    protected static $auditEvents = ['created', 'updated', 'deleted'];

    // Relación con el modelo Usuario
    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }
}
