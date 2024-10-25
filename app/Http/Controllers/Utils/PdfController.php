<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class PdfController extends Controller
{
/*     public function generarPDF(Request $request)
    {
        // Asegúrate de que se estén recibiendo datos
        $data = $request->all();
        if (empty($data)) {
            return response()->json(['error' => 'No data provided'], 400);
        }

        try {
            // Crear instancia de Mpdf
            $mpdf = new Mpdf();

            // Cargar vista en HTML
            $html = view('pdf.formulario1', compact('data'))->render();

            // Escribir HTML al PDF
            $mpdf->WriteHTML($html);

            // Establecer encabezados para la descarga
            return response()->stream(function () use ($mpdf) {
                $mpdf->Output('formulario-1.pdf', 'D');
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="formulario-1.pdf"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } */
}
