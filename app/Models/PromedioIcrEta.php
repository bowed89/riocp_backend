<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromedioIcrEta extends Model
{
    use HasFactory;
    protected $table = 'promedio_icr_eta';
    protected $fillable = [
        'c11000',
        'c12000',
        'c13000',
        'c14000',
        'c15000',
        'c16000',
        'c17000',
        'c19211',
        'c19212',
        'c19216',
        'c19219',
        'c19220',
        'c19230',
        'c19260',
        'c19270',
        'c19280',
        'c19300',
        'c19400',
        'gestion',
        'total',
        'c19212_org_119_idh',
        'c19212_org_119_50_percent',
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
