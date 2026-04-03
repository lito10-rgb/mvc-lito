<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/* PayPal SDK */
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

/* Mercado Pago SDK (nuevo) */
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** ===========================
     *  PANTALLA PRINCIPAL CHECKOUT
     *  =========================== */
    public function index()
    {
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $subtotal = $this->calcularSubtotal($carrito);
        $envio = $this->calcularEnvio($subtotal);
        $total = round($subtotal + $envio, 2);

        return view('checkout.index', compact('carrito', 'subtotal', 'envio', 'total'));
    }

    /** ===========================
     *  PROCESAR PAGO
     *  =========================== */
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

        $subtotal = $this->calcularSubtotal($carrito);
        $envio = $this->calcularEnvio($subtotal);
        $total = round($subtotal + $envio, 2);

        return match ($metodo) {
            'simulado' => $this->simulateSuccess('simulado', $total),
            'mercadopago' => $this->crearPreferenciaMercadoPago($carrito, $total),
            'paypal' => $this->crearOrdenPayPal($carrito, $total),
            default => back()->with('error', 'Método de pago no soportado.'),
        };
    }

    /** ===========================
     *  SIMULADOR DE PAGO
     *  =========================== */
    protected function simulateSuccess($metodo, $total)
    {
        session()->forget('carrito');
        return redirect()->route('checkout.success')->with('success', "Pago simulado con {$metodo}. Total: {$total} USD");
    }

    /** ===========================
     *  MERCADO PAGO (NUEVO SDK)
     *  =========================== */
    protected function crearPreferenciaMercadoPago(array $carrito, float $total)
    {
        try {
            // Configurar token
            $accessToken = env('MERCADOPAGO_ACCESS_TOKEN');
            MercadoPagoConfig::setAccessToken($accessToken);

            $client = new PreferenceClient();

            // Crear ítems
            $items = [];
            foreach ($carrito as $item) {
                $items[] = [
                    "title" => $item['nombre'] ?? 'Producto sin nombre',
                    "quantity" => (int) ($item['cantidad'] ?? 1),
                    "unit_price" => (float) ($item['precio'] ?? 0),
                    "currency_id" => "PEN",
                ];
            }

            // Agregar envío si corresponde
            $subtotal = array_sum(array_map(fn($i) => $i['unit_price'] * $i['quantity'], $items));
            if ($total > $subtotal) {
                $items[] = [
                    "title" => "Costo de envío",
                    "quantity" => 1,
                    "unit_price" => $total - $subtotal,
                    "currency_id" => "PEN",
                ];
            }

            // Crear preferencia
            $preference = $client->create([
                "items" => $items,
                "back_urls" => [
                    "success" => route('mercadopago.success'),
                    "failure" => route('mercadopago.failure'),
                    "pending" => route('mercadopago.pending'),
                ],
                "auto_return" => "approved",
            ]);

            // Redirigir al link de pago
            $url = $preference->init_point ?? $preference->sandbox_init_point ?? null;

            if (!$url) {
                Log::error('Mercado Pago: init_point no generado', ['preference' => $preference]);
                return redirect()->route('checkout')->with('error', 'Error al iniciar pago con Mercado Pago.');
            }

            return redirect($url);

        } catch (MPApiException $apiError) {
            Log::error('Mercado Pago API error', ['error' => $apiError->getMessage(), 'response' => $apiError->getApiResponse()]);
            return redirect()->route('checkout')->with('error', 'Error en la API de Mercado Pago.');
        } catch (\Exception $e) {
            Log::error('Mercado Pago error general', ['msg' => $e->getMessage()]);
            return redirect()->route('checkout')->with('error', 'No se pudo conectar con Mercado Pago.');
        }
    }

    /** ===========================
     *  PAYPAL
     *  =========================== */
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

    /** ===========================
     *  RESULTADOS PAYPAL
     *  =========================== */
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
            Log::info('PayPal capture', ['result' => $response->result]);
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

    public function success()
    {
        return view('checkout.success');
    }

    /** ===========================
     *  HELPERS
     *  =========================== */
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
        return $subtotal >= 100 ? 0.0 : 5.00;
    }
}
