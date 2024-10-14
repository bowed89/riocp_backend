<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoDerivado extends Model
{
    protected $table = 'estados_derivado';

    protected $fillable = [
        'tipo',
        'estado'
    ];}
