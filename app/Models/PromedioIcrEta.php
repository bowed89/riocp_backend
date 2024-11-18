<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromedioIcrEta extends Model
{
    use HasFactory;
    protected $table = 'promedio_icr_eta';
    protected $fillable = [
        'entidad',
        'gestion',
        '11000',
        '12000',
        '13000',
        '14000',
        '15000',
        '16000',
        '17000',
        '19211',
        '19212',
        '19216',
        '19219',
        '19220',
        '19230',
        '19260',
        '19270',
        '19280',
        '19300',
        '19400',
        'total',
        '19212_org_119_idh',
        '19212_org_119_50_percent',
        'icr',
        'epre_19212',
        'epre_41_119_19211',
        'epre_41_119_19212',
        'sumatoria_epre_19211_19212',
        'epre_19216',
        'epre_41_119_19216',
        'epre_19219',
        'epre_41_119_19219',
        'epre_19220',
        'epre_41_119_19220',
        'epre_19230',
        'epre_41_119_19230',
        'epre_19260',
        'epre_41_119_19260',
        'epre_19270',
        'epre_41_119_19270',
        'epre_19280',
        'epre_41_119_19280',
        'epre_19300',
        'epre_41_119_19300',
        'epre_19400',
        'epre_41_119_19400',
        'total_41_119'
    ];
}
