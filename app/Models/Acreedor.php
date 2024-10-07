<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acreedor extends Model
{
    use HasFactory;
    protected $table = 'acreedores';

    protected $fillable = [
        'nombre',
        'codigo',
        'estado'
    ];
}
