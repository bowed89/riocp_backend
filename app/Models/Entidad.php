<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model 
{
    use HasFactory;
    protected $table = 'entidades';

      
    protected $fillable = [
        'denominacion',
        'sigla'
    ];

    protected static $auditEvents = ['created', 'updated', 'deleted'];

}
