<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // <- importante
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/* PayPal SDK */
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

/* MercadoPago SDK */
use MercadoPago\SDK as MP_SDK;
use MercadoPago\Preference;
use MercadoPago\Item as MPItem;

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
        $subtotal = $this->calcularSubtotal($carrito);
        $envio = $this->calcularEnvio($subtotal); // lógica de envío (puedes cambiar)
        $total = round($subtotal + $envio, 2);

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

        // Calcula total en servidor
        $subtotal = $this->calcularSubtotal($carrito);
        $envio = $this->calcularEnvio($subtotal);
        $total = round($subtotal + $envio, 2);

        if ($metodo === 'simulado') {
            return $this->simulateSuccess('simulado', $total);
        }

        if ($metodo === 'mercadopago') {
            return $this->crearPreferenciaMercadoPago($carrito, $total);
        }

        if ($metodo === 'paypal') {
            return $this->crearOrdenPayPal($carrito, $total);
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
     * Crear preferencia Mercado Pago y redirigir al init_point (sandbox o live)
     */
    protected function crearPreferenciaMercadoPago(array $carrito, float $total)
    {
        try {
            MP_SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));

            $preference = new Preference();

            // Mapeo: puedes enviar cada producto como item; aquí uso 1 item resumen
            $item = new MPItem();
            $item->title = 'Pedido - ' . config('app.name');
            $item->quantity = 1;
            $item->unit_price = (float) $total;

            $preference->items = [$item];

            $preference->back_urls = [
                'success' => route('checkout.success'),
                'failure' => route('checkout'),
                'pending' => route('checkout'),
            ];
            $preference->auto_return = 'approved';
            $preference->save();

            // Si usas sandbox, preferencia puede exponer sandbox_init_point
            $init = $preference->sandbox_init_point ?? $preference->init_point ?? null;
            if ($init) {
                return redirect($init);
            }

            Log::error('MercadoPago: init_point no generado', ['preference' => $preference]);
            return redirect()->route('checkout')->with('error', 'Error al iniciar Mercado Pago.');
        } catch (\Exception $e) {
            Log::error('MercadoPago error', ['msg' => $e->getMessage()]);
            return redirect()->route('checkout')->with('error', 'No se pudo conectar con Mercado Pago: ' . $e->getMessage());
        }
    }

    /**
     * Crear orden PayPal (sandbox) y redirigir al href approve
     */
    protected function crearOrdenPayPal(array $carrito, float $total)
    {
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('checkout')->with('error', 'Credenciales PayPal no configuradas.');
        }

        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $client = new PayPalHttpClient($environment);

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');

        $currency = env('CHECKOUT_CURRENCY', 'USD');

        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($total, 2, '.', '')
                    ],
                    'description' => 'Compra en ' . config('app.name'),
                ]
            ],
            'application_context' => [
                'return_url' => route('checkout.paypal.success'),
                'cancel_url' => route('checkout.paypal.cancel'),
            ]
        ];

        try {
            $response = $client->execute($request);

            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    return redirect($link->href);
                }
            }

            Log::error('PayPal: approve link no encontrado', ['response' => $response]);
            return redirect()->route('checkout')->with('error', 'No se pudo iniciar PayPal.');
        } catch (\Exception $e) {
            Log::error('PayPal create order error', ['msg' => $e->getMessage()]);
            return redirect()->route('checkout')->with('error', 'Error al comunicarse con PayPal.');
        }
    }

    /**
     * PayPal callback (capture)
     */
    public function paypalSuccess(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return redirect()->route('checkout')->with('error', 'Token PayPal no recibido.');
        }

        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');
        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $client = new PayPalHttpClient($environment);

        $capture = new OrdersCaptureRequest($token);
        $capture->prefer('return=representation');

        try {
            $response = $client->execute($capture);
            // Guarda la transacción en BD si quieres: $response->result contiene la info
            Log::info('PayPal capture', ['result' => $response->result]);

            // Limpia carrito y redirige a success
            session()->forget('carrito');
            return redirect()->route('checkout.success')->with('success', 'Pago con PayPal completado (sandbox).');
        } catch (\Exception $e) {
            Log::error('PayPal capture error', ['msg' => $e->getMessage()]);
            return redirect()->route('checkout')->with('error', 'Error al capturar pago en PayPal.');
        }
    }

    public function paypalCancel()
    {
        return redirect()->route('checkout')->with('error', 'Pago PayPal cancelado por el usuario.');
    }

    /**
     * Vista de éxito.
     */
    public function success()
    {
        return view('checkout.success');
    }

    /* ---------- Helpers ---------- */

    protected function calcularSubtotal(array $carrito): float
    {
        $sum = 0.0;
        foreach ($carrito as $item) {
            $sum += ($item['precio'] ?? 0) * ($item['cantidad'] ?? 1);
        }
        return round($sum, 2);
    }

    protected function calcularEnvio(float $subtotal): float
    {
        if ($subtotal >= 100) return 0.0;
        return 5.00;
    }
}
