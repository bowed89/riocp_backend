<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menus';


    protected $fillable = [
        'nombre',
        'url',
        'icono',
        'show_menu',
        'estado',
        'rol_id',
        'tipo_id'
    ];

    protected static $auditEvents = ['created', 'updated', 'deleted'];

}
