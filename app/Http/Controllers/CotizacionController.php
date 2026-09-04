<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CotizacionController extends Controller
{
    public function solicitar($id)
    {
        $negocioId = negocio_actual_id();
        $producto = Producto::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->findOrFail($id);
        return view('cotizacion.solicitar', compact('producto'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente'        => 'required|string|max:255',
            'correo'         => 'nullable|email|max:255',
            'telefono'       => 'nullable|string|max:50',
            'producto'       => 'required|string|max:255',
            'descripcion'    => 'nullable|string',
            'cantidad'       => 'required|integer|min:1',
            'precio_unitario'=> 'required|numeric|min:0',
        ]);

        $validated['subtotal'] = $validated['cantidad'] * $validated['precio_unitario'];
        $validated['impuesto'] = 0;
        $validated['total']    = $validated['subtotal'];
        $validated['estado']   = 'pendiente';
        $validated['fecha']    = now()->format('Y-m-d');

        Cotizacion::create($validated);

        // Notificaciones por correo
        try {
            $this->enviarCorreos($validated);
        } catch (\Exception $e) {
            // No rompe la solicitud aunque falle el correo
        }

        return redirect()->back()->with('success', 'Cotización enviada correctamente. Te contactaremos pronto.');
    }

    protected function enviarCorreos(array $validated)
    {
        $negocio = negocio_actual();
        $negocioNombre = $negocio?->nombre
            ?: \Illuminate\Support\Str::title(str_replace('-', ' ', str_replace('.com', '', config('negocio.dominios')[request()->getHost()] ?? 'Equipos y Maquinas')));

        $data = [
            'cliente'      => $validated['cliente'],
            'correo'       => $validated['correo'] ?? '',
            'telefono'     => $validated['telefono'] ?? '',
            'producto'     => $validated['producto'],
            'descripcion'  => $validated['descripcion'] ?? '',
            'cantidad'     => $validated['cantidad'],
            'precio'       => $validated['precio_unitario'],
            'subtotal'     => $validated['subtotal'],
            'negocio'      => $negocioNombre,
        ];

        // Notificación al correo del negocio/dominio actual
        $destinatarios = $this->correosNotificacion($negocio);

        Mail::send('emails.solicitud_notificacion', $data, function ($message) use ($destinatarios, $data) {
            $message->to($destinatarios)
                ->subject('Nueva solicitud de cotización: ' . $data['producto']);
        });

        // Confirmación al solicitante
        if (!empty($validated['correo'])) {
            Mail::send('emails.solicitud_confirmacion', $data, function ($message) use ($data) {
                $message->to($data['correo'])
                    ->subject('Recibimos tu solicitud de cotización');
            });
        }
    }

    /**
     * Resuelve el/los correo(s) del negocio/dominio actual donde notificar.
     * Puede contener varios separados por ';' o ',', o caer al correo del dominio.
     */
    protected function correosNotificacion(?\App\Models\Negocio $negocio): array
    {
        $crudo = $negocio?->footer_email ?: '';

        if (trim($crudo) !== '') {
            $partes = preg_split('/[;,]/', $crudo);
            $correos = [];
            foreach ($partes as $p) {
                $c = trim($p);
                if (filter_var($c, FILTER_VALIDATE_EMAIL)) {
                    $correos[] = $c;
                }
            }
            if (!empty($correos)) {
                return $correos;
            }
        }

        // Fallback: correo del dominio del negocio, o el from general
        $host = request()->getHost();
        $correoDominio = 'informes@' . $host;
        if (filter_var($correoDominio, FILTER_VALIDATE_EMAIL)) {
            return [$correoDominio];
        }

        return [config('mail.from.address')];
    }
}
