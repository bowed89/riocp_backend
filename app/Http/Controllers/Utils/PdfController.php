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
        $filePath = $this->pdfService->generarPdf($html);

        return response()->download($filePath);
    }
}
