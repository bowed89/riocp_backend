<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Http\Services\Utils\PdfService;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function generarPDF(Request $request)
    {
        $datos = $request->all();
        $html = $this->pdfService->generarHtml($datos);
        $pdfContent = $this->pdfService->generarPdf($html); 
        
        // archivo PDF como una respuesta de descarga
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="formulario1.pdf"');
    }
}
