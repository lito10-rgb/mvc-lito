<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/* PayPal SDK */
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

/* Mercado Pago SDK v3 */
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Protege rutas si quieres solo usuarios autenticados
        $this->middleware('auth');

        // Configurar Mercado Pago si existe token
        $mpToken = env('MERCADOPAGO_ACCESS_TOKEN');
        if (!empty($mpToken)) {
            MercadoPagoConfig::setAccessToken($mpToken);
        }
    }

    // Mostrar checkout
    public function index()
    {
        $carrito = session('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $subtotal = $this->calcularSubtotal($carrito);
        $envio = $this->calcularEnvio($subtotal);
        $total = round($subtotal + $envio, 2);

        return view('checkout.index', compact('carrito','subtotal','envio','total'));
    }

    // Procesar pago (mercadopago | paypal | simulado)
    public function pay(Request $request)
    {
        $request->validate(['metodo' => 'required|string|in:mercadopago,paypal,simulado']);
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
            default => back()->with('error','Método no soportado'),
        };
    }

    protected function simulateSuccess($metodo, $total)
    {
        session()->forget('carrito');
        return redirect()->route('checkout.success')->with('success', "Pago simulado con {$metodo}. Total: {$total}");
    }

    // Crear preferencia Mercado Pago (SDK v3)
 use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Crear preferencia via REST (debuggable) — reemplaza temporalmente el método original.
 */
protected function crearPreferenciaMercadoPago(array $carrito, float $total)
{
    $token = config('services.mercadopago.token') ?: env('MERCADOPAGO_ACCESS_TOKEN');

    if (empty($token)) {
        Log::error('MP DEBUG: token vacío');
        return redirect()->route('checkout.index')->with('error', 'Token de Mercado Pago no configurado.');
    }

    // Crear orden pending
    $userId = auth()->id() ?: null;
    $order = Order::create([
        'user_id' => $userId,
        'amount' => $total,
        'status' => 'pending',
        'payload' => ['carrito' => $carrito],
    ]);

    // Construir items con tipos seguros
    $items = [];
    foreach ($carrito as $p) {
        $items[] = [
            'title' => $p['titulo'] ?? $p['nombre'] ?? 'Producto',
            'quantity' => (int) ($p['cantidad'] ?? 1),
            'unit_price' => (float) ($p['precio'] ?? 0),
            'currency_id' => 'PEN',
        ];
    }

    $subtotal = array_sum(array_map(fn($i) => $i['unit_price'] * $i['quantity'], $items));
    if ($total > $subtotal) {
        $items[] = [
            'title' => 'Costo de envío',
            'quantity' => 1,
            'unit_price' => round($total - $subtotal, 2),
            'currency_id' => 'PEN',
        ];
    }

    // Payload completo (copia exactamente lo que usa tu app)
    $payload = [
        'items' => $items,
        'back_urls' => [
            'success' => route('mercadopago.success', [], true),
            'failure' => route('mercadopago.failure', [], true),
            'pending' => route('mercadopago.pending', [], true),
        ],
        'auto_return' => 'approved',
        'notification_url' => route('mercadopago.notification', [], true),
        'external_reference' => (string) $order->id,
    ];

    Log::info('MP DEBUG - payload', ['order_id' => $order->id, 'payload' => $payload]);

    try {
        // 1) Intento normal (verifica SSL)
        $resp = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/checkout/preferences', $payload);

        Log::info('MP DEBUG - response', ['status' => $resp->status(), 'body' => $resp->body(), 'headers' => $resp->headers()]);

        // Si ok -> redirige
        if ($resp->successful() && in_array($resp->status(), [200,201])) {
            $body = $resp->json();
            $preferenceId = $body['id'] ?? null;
            $sandboxUrl = $body['sandbox_init_point'] ?? $body['init_point'] ?? null;

            if ($preferenceId) {
                $order->update([
                    'preference_id' => $preferenceId,
                    'payload' => array_merge($order->payload ?? [], ['preference_raw' => $body]),
                ]);
                session(['mp_preference_id' => $preferenceId]);
            }

            if ($sandboxUrl) {
                return redirect()->away($sandboxUrl);
            }

            return redirect()->route('checkout.index')->with('error', 'MP DEBUG: no sandbox/init url en body: ' . json_encode($body));
        }

        // Si no fue successful -> intenta mostrar body crudo al usuario (útil en dev)
        $raw = $resp->body() ?: '{}';
        $order->update(['status' => 'failed', 'payload' => array_merge($order->payload ?? [], ['mp_error' => $raw])]);
        Log::error('MP DEBUG - non-success', ['status' => $resp->status(), 'body' => $raw]);

        // Si body está vacío, intentar reintento sin verificación SSL (diagnóstico)
        if (empty(trim($raw))) {
            Log::warning('MP DEBUG - body vacío. Intentando reintento con verify => false (solo debug).');
            $resp2 = Http::withOptions(['verify' => false])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post('https://api.mercadopago.com/checkout/preferences', $payload);

            Log::info('MP DEBUG - retry response', ['status' => $resp2->status(), 'body' => $resp2->body(), 'headers' => $resp2->headers()]);

            if ($resp2->successful() && in_array($resp2->status(), [200,201])) {
                $body2 = $resp2->json();
                $sandboxUrl2 = $body2['sandbox_init_point'] ?? $body2['init_point'] ?? null;
                if ($sandboxUrl2) {
                    $order->update(['preference_id' => $body2['id'] ?? null, 'payload' => array_merge($order->payload ?? [], ['preference_raw' => $body2])]);
                    session(['mp_preference_id' => $body2['id'] ?? null]);
                    return redirect()->away($sandboxUrl2);
                }
            }

            $raw2 = $resp2->body() ?: '{}';
            $order->update(['status' => 'failed', 'payload' => array_merge($order->payload ?? [], ['mp_error_retry' => $raw2])]);
            return redirect()->route('checkout.index')->with('error', 'Error en Mercado Pago (reintento): ' . substr($raw2, 0, 1000));
        }

        // Mostrar el error que devolvió MP (útil)
        return redirect()->route('checkout.index')->with('error', 'Error en Mercado Pago: ' . substr($raw, 0, 1000));

    } catch (\Throwable $e) {
        // Log completo
        Log::error('MP DEBUG - exception', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        // Mostrar mensaje concreto SOLO en entorno local
        if (app()->environment('local')) {
            return redirect()->route('checkout.index')->with('error', 'EXCEPTION MP: ' . $e->getMessage());
        }
        $order->update(['status' => 'failed', 'payload' => array_merge($order->payload ?? [], ['exception' => $e->getMessage()])]);
        return redirect()->route('checkout.index')->with('error', 'Error conectando con Mercado Pago. Revisa logs.');
    }
}

    // Crear orden PayPal (sandbox)
    protected function crearOrdenPayPal(array $carrito, float $total)
    {
        $clientId = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');
        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('checkout')->with('error','Credenciales PayPal no configuradas.');
        }

        $environment = new SandboxEnvironment($clientId, $clientSecret);
        $client = new PayPalHttpClient($environment);
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');

        $currency = env('CHECKOUT_CURRENCY','USD');

        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => ['currency_code' => $currency,'value' => number_format($total,2,'.','')],
                'description' => 'Compra en ' . config('app.name'),
            ]],
            'application_context' => ['return_url' => route('checkout.paypal.success'),'cancel_url' => route('checkout.paypal.cancel')]
        ];

        try {
            $response = $client->execute($request);
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') return redirect()->away($link->href);
            }
            Log::error('PayPal approve link no encontrado', ['response' => $response]);
            return redirect()->route('checkout')->with('error','No se pudo iniciar PayPal.');
        } catch (\Exception $e) {
            Log::error('PayPal create order error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('checkout')->with('error','Error al comunicarse con PayPal.');
        }
    }

    // PayPal capture
    public function paypalSuccess(Request $request)
    {
        $token = $request->query('token');
        if (!$token) return redirect()->route('checkout')->with('error','Token PayPal no recibido.');

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
            return redirect()->route('checkout.success')->with('success','Pago con PayPal completado (sandbox).');
        } catch (\Exception $e) {
            Log::error('PayPal capture error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('checkout')->with('error','Error al capturar pago en PayPal.');
        }
    }

    public function paypalCancel()
    {
        return redirect()->route('checkout')->with('error','Pago PayPal cancelado por el usuario.');
    }

    // Callbacks Mercado Pago
    public function success(Request $request)
    {
        return view('checkout.success', ['data' => $request->all()]);
    }

    public function failure(Request $request)
    {
        Log::warning('MP failure callback', $request->all());
        return view('checkout.failure', ['data' => $request->all()]);
    }

    public function pending(Request $request)
    {
        Log::info('MP pending callback', $request->all());
        return view('checkout.pending', ['data' => $request->all()]);
    }

    // Webhook
    public function mercadoPagoNotification(Request $request)
    {
        Log::info('MP notification received', $request->all());
        $id = $request->get('id') ?? $request->input('id') ?? null;
        if ($id) Log::info('MP notification id', ['id' => $id]);
        return response()->json(['received' => true], 200);
    }

    // Helpers
    protected function calcularSubtotal(array $carrito): float
    {
        $sum = 0.0;
        foreach ($carrito as $item) {
            $sum += ($item['precio'] ?? 0) * ($item['cantidad'] ?? 1);
        }
        return round($sum,2);
    }

    protected function calcularEnvio(float $subtotal): float
    {
        return $subtotal >= 100 ? 0.0 : 5.00;
    }
}
