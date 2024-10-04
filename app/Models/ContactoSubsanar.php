<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoSubsanar extends Model
{
    use HasFactory;
    protected $table = 'contactos_subsanar';

    protected $fillable = [
        'nombre_completo',
        'cargo',
        'correo_electronico',
        'estado'
    ];
}
