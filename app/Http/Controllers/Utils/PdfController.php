<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;


class PdfController extends Controller
{

    /* 
    public function generarPDF()
    {
        Browsershot::html('<h1>Hello world!!</h1>')
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->save('example.pdf');

        return response()->json(['message' => 'PDF generado exitosamente', 'file' => url('example.pdf')]);
    } */



    public function generarPDF(Request $request)
    {
        $datos = $request->all();

        // Renderizar la vista Blade sin datos
        $html = View::make('pdf.formulario1', compact('datos'))->render();

        // Generar el PDF usando Browsershot
        Browsershot::html($html)
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->showBackground() // Asegura que los fondos de color se muestren
            ->margins(0, 0, 0, 0) // Eliminar márgenes
            ->save('formulario1.pdf');

        return response()->download(public_path('formulario1.pdf'));
    }

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
