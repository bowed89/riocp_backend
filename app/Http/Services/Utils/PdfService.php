<?php

namespace App\Http\Services\Utils;

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;

class PdfService
{
    public function generarHtml($datos)
    {
        return View::make('pdf.formulario1', compact('datos'))->render();
    }

    public function generarPdf($html)
    {
        $filePath = public_path('formulario1.pdf');
        Browsershot::html($html)
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->showBackground()
            ->margins(0, 0, 0, 0)
            ->save($filePath);

        return $filePath;
    }
}