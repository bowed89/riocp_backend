<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;


class Rol extends Model 
{
    use HasFactory;
    protected $table = 'roles';

    protected $fillable = [
        'rol',
        'descripcion',
        'estado'
    ];

   
}
