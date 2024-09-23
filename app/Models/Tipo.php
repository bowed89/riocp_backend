<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable; 
use OwenIt\Auditing\Auditable as AuditableTrait;

class tipos extends Model implements Auditable
{
    use HasFactory, AuditableTrait;
    protected $table = 'tipos';
    
    protected $fillable = [
        'tipo'
    ];

    protected static $auditEvents = ['created', 'updated', 'deleted'];

}
