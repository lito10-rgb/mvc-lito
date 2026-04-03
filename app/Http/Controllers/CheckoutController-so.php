<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // <- importante
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Asegura que solo usuarios autenticados accedan a checkout
        $this->middleware('auth');
    }

    /**
     * Mostrar resumen del checkout.
     */
    public function index()
    {
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        // Calcular totales simples
        $subtotal = 0;
        foreach ($carrito as $id => $item) {
            $subtotal += ($item['precio'] * $item['cantidad']);
        }

        $envio = 0; // lógica de envío si la tienes
        $total = $subtotal + $envio;

        return view('checkout.index', compact('carrito', 'subtotal', 'envio', 'total'));
    }

    /**
     * Procesar pago (seleccionar pasarela).
     */
    public function pay(Request $request)
    {
        $request->validate([
            'metodo' => 'required|string|in:mercadopago,paypal,simulado',
        ]);

        $metodo = $request->input('metodo');
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Carrito vacío.');
        }

        // Calcula total
        $total = 0;
        foreach ($carrito as $item) {
            $total += ($item['precio'] * $item['cantidad']);
        }

        // Si implementas SDKs reales, reemplaza los bloques simulados por la integración.
        if ($metodo === 'mercadopago') {
            // Aquí va la integración con Mercado Pago (SDK). Por ahora simulamos:
            return $this->simulateSuccess('mercadopago', $total);
        }

        if ($metodo === 'paypal') {
            // Aquí va integración con PayPal (SDK). Por ahora simulamos:
            return $this->simulateSuccess('paypal', $total);
        }

        if ($metodo === 'simulado') {
            return $this->simulateSuccess('simulado', $total);
        }

        return back()->with('error', 'Método de pago no soportado.');
    }

    /**
     * Simula pago exitoso: limpia carrito y crea mensaje.
     */
    protected function simulateSuccess($metodo, $total)
    {
        // Aquí podrías crear un registro de pedido en BD si quieres.
        session()->forget('carrito');

        return redirect()->route('checkout.success')
            ->with('success', "Pago simulado con {$metodo}. Total: {$total} USD");
    }

    /**
     * Vista de éxito.
     */
    public function success()
    {
        return view('checkout.success');
    }
}
