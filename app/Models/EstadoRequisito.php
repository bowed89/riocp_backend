<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoRequisito extends Model
{
    protected $table = 'estados_requisito';

    protected $fillable = [
        'tipo',
        'estado'
    ];
}
