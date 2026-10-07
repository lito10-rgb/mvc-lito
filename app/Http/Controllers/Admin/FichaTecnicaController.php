<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class FichaTecnicaController extends Controller
{
    public function cafe()
    {
        try {
            $fecha = now()->locale('es')->translatedFormat('d \d\e F \d\e Y');

            $pdf = Pdf::loadView('admin.ficha-tecnica.cafe-pdf', compact('fecha'))
                ->setPaper('a4', 'portrait');

            $response = $pdf->stream('ficha-tecnica-cafe-verde-comercial.pdf');
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

            return $response;
        } catch (\Throwable $e) {
            \Log::error('FichaTecnicaController cafe: ' . $e->getMessage(), ['tr' => $e->getTraceAsString()]);
            return back()->with('error', 'No se pudo generar la ficha en PDF: ' . $e->getMessage());
        }
    }
}