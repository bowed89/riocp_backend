<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class tipos extends Model
{
    use HasFactory;
    protected $table = 'tipos';
    
    protected $fillable = [
        'tipo'
    ];

    protected static $auditEvents = ['created', 'updated', 'deleted'];

}
