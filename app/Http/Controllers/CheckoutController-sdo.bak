<?php

namespace App\Http\Controllers;

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

        $envio = 0; // puedes calcular envío según lógica
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

        // ---------- MERCADO PAGO (ejemplo: comentar/activar si instalas SDK) ----------
        if ($metodo === 'mercadopago') {
            // 1) Instalar SDK: composer require mercadopago/dx-php
            // 2) Agregar en .env: MERCADOPAGO_ACCESS_TOKEN=...
            // 3) Descomenta e implementa:
            //
            // \MercadoPago\SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
            // $preference = new \MercadoPago\Preference();
            // $items = [];
            // foreach ($carrito as $id => $it) {
            //     $item = new \MercadoPago\Item();
            //     $item->title = $it['titulo'];
            //     $item->quantity = $it['cantidad'];
            //     $item->unit_price = (float) $it['precio'];
            //     $items[] = $item;
            // }
            // $preference->items = $items;
            // $preference->back_urls = [
            //     'success' => route('checkout.success'),
            //     'failure' => route('checkout.failure'),
            //     'pending' => route('checkout.pending'),
            // ];
            // $preference->auto_return = 'approved';
            // $preference->save();
            //
            // return redirect($preference->init_point);

            // Si aún no integras, simulamos:
            return $this->simulateSuccess('mercadopago', $total);
        }

        // ---------- PAYPAL (ejemplo) ----------
        if ($metodo === 'paypal') {
            // 1) Instalar SDK: composer require paypal/paypal-checkout-sdk
            // 2) Configurar CLIENT_ID y SECRET en .env
            // 3) Implementar creación de orden y redirección a PayPal
            //
            // Código real requiere SDK y manejo de webhooks/return urls.
            //
            // Simulamos:
            return $this->simulateSuccess('paypal', $total);
        }

        // ---------- MÉTODO SIMULADO (para pruebas locales) ----------
        if ($metodo === 'simulado') {
            return $this->simulateSuccess('simulado', $total);
        }

        return back()->with('error', 'Método de pago no soportado.');
    }

    /**
     * Simula pago exitoso: limpia carrito y crea mensaje.
     * En implementación real reemplazar por lógica de creación de orden y verificación.
     */
    protected function simulateSuccess($metodo, $total)
    {
        // Aquí podrías crear un registro de pedido en BD si quieres.
        // Por ahora limpiamos el carrito y devolvemos a una vista de éxito.
        session()->forget('carrito');

        // Opcional: guardar orden en BD: Order::create([...])
        return redirect()->route('checkout.success')->with('success', "Pago simulado con {$metodo}. Total: {$total} USD");
    }

    /**
     * Vista de éxito (se puede crear ruta/view).
     */
    public function success()
    {
        return view('checkout.success');
    }
}
