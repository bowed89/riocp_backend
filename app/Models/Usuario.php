<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'nombre_usuario',
        'ci',
        'password',
        'estado',
        'rol_id',
        'entidad_id'
    ];

    // ocultar el campo 'password' en las auditorías
    protected $hidden = [
        'password',
    ];

    protected static $auditEvents = ['created', 'updated', 'deleted'];


    // Relación con el modelo Entidad
    public function entidad()
    {
        return $this->belongsTo(Entidad::class);
    }
}
