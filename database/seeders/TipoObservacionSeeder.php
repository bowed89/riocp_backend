<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoObservacionSeeder extends Seeder
{
    public function run(): void
    {
        /*         DB::table('tipos_observaciones_tecnico')->insert([
            [
                'observacion' =>
                'Carta de solicitud dirigida a la Viceministra
                del Tesoro y Crédito Público, suscrita por la Maxima 
                Autoridad Ejecutiva (MAE).',
            ],
            [
                'observacion' =>
                'Formulario 1 de “Solicitud de Registro de Inicio de 
                Operaciones de Crédito Público”, en el que se describen 
                las características principales de la operación de crédito 
                público que se solicita iniciar (uno por acreedor),
                 así como el respectivo Cronograma de Pagos y Desembolsos.',
            ],
            [
                'observacion' =>
                'Formulario 2 de “Información de Deuda”.',
            ],
            [
                'observacion' =>
                'Formulario 3 de “Cronograma del Servicio de la Deuda”.',
            ],
            [
                'observacion' =>
                'Formulario 4 de “Cronograma de Desembolsos Programados y/o Estimados”.',
            ],
            [
                'observacion' =>
                'Anexo: Cronograma de Pagos.',
            ],
            [
                'observacion' =>
                'Anexo: Cronograma de Desembolsos.',
            ],
            [
                'observacion' =>
                'Anexo: Información Financiera.',
            ],
            [
                'observacion' =>
                'Anexo: Certificado RIOCP no Vigente.',
            ],
        ]); */

        DB::table('tipos_observaciones_tecnico')->insert([
            [
                'observacion' =>
                '1. Carta de solicitud dirigida a la Viceministra
                del Tesoro y Crédito Público, suscrita por la Maxima 
                Autoridad Ejecutiva (MAE).',
            ],
            [
                'observacion' =>
                '2. Formulario 1 de “Solicitud de Registro de Inicio de 
                Operaciones de Crédito Público”, en el que se describen 
                las características principales de la operación de crédito 
                público que se solicita iniciar (uno por acreedor),
                 así como el respectivo Cronograma de Pagos y Desembolsos.',
            ],
            [
                'observacion' =>
                '2.1 Anexo: Cronograma de Pagos y Desembolsos.',
            ],
            [
                'observacion' =>
                '2.2 Anexo: Cronograma de Desembolsos.',
            ],
            
            [
                'observacion' =>
                '3. Formulario 2 de “Información de Deuda”.',
            ],
            [
                'observacion' =>
                '3.1 Anexo: Certificado no Vigente.(es del anexo del formulario 2 de la pregunta 4 xsi)',
            ],
            [
                'observacion' =>
                '4. Formulario 3 de “Cronograma del Servicio de la Deuda”.',
            ],
            [
                'observacion' =>
                '5. Formulario 4 de “Cronograma de Desembolsos Programados y/o Estimados”.',
            ],
            [
                'observacion' =>
                'Anexo: Segip Móvil. Información Financiera.',
            ],
            [
                'observacion' =>
                'Anexo: Certificado RIOCP no Vigente. (este ya no va esta arriba, me confundi)',
            ],
        ]);
    }
}
