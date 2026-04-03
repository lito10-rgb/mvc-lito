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
  protected function crearPreferenciaMercadoPago(array $carrito, float $total)
{
    // $token = env('MERCADOPAGO_ACCESS_TOKEN');
     $token = config('services.mercadopago.token');
    if (empty($token)) {
        Log::warning('MERCADOPAGO_ACCESS_TOKEN missing');
        return redirect()->route('checkout.index')->with('error', 'Token de Mercado Pago no configurado.');
    }

    // Crear orden local antes de preference
    // $userId = auth()->check() ? auth()->id() : null;
    $userId = auth()->id() ?: null;
    $order = Order::create([
        'user_id' => $userId,
        'amount' => $total,
        'status' => 'pending',
        'payload' => ['carrito' => $carrito],
    ]);

    // Construir items con tipos correctos
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
            'currency_id' => 'PEN'
        ];
    }

    // Payload REST (usamos external_reference = id de nuestra orden)
    $payload = [
        'items' => $items,
        'back_urls' => [
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failure'),
            'pending' => route('mercadopago.pending'),
        ],
        'auto_return' => 'approved',
        'notification_url' => route('mercadopago.notification'),
        'external_reference' => (string) $order->id,
    ];

    Log::info('MP REST creating preference payload', ['order_id' => $order->id, 'payload' => $payload]);

    try {
        $resp = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/checkout/preferences', $payload);

        // Loguear todo para debugging
        Log::info('MP REST response', [
            'status' => $resp->status(),
            'headers' => $resp->headers(),
            'body' => $resp->body()
        ]);

        // Si OK (201) leer JSON
        if ($resp->successful() && in_array($resp->status(), [200,201])) {
            $body = $resp->json();
            $preferenceId = $body['id'] ?? null;
            $sandboxUrl = $body['sandbox_init_point'] ?? $body['init_point'] ?? null;

            // guardar preference_id en la orden
            if ($preferenceId) {
                $order->update([
                    'preference_id' => $preferenceId,
                    'payload' => array_merge($order->payload ?? [], ['preference_raw' => $body])
                ]);
                session(['mp_preference_id' => $preferenceId]);
            }

            if (empty($sandboxUrl)) {
                Log::error('MP REST: sandbox_init_point vacío', ['body' => $body]);
                return redirect()->route('checkout.index')->with('error', 'No se devolvió la URL de pago. Revisa logs.');
            }

            return redirect()->away($sandboxUrl);
        }

        // Si no fue successful -> mostrar error detallado (body crudo)
        $raw = $resp->body();
        // marcar orden como failed y guardar body para debugging
        $order->update(['status' => 'failed', 'payload' => array_merge($order->payload ?? [], ['mp_error' => $raw])]);

        $msg = 'Error en Mercado Pago: ' . ($raw ?: 'respuesta vacía');
        Log::error('MP REST non-success', ['status' => $resp->status(), 'body' => $raw]);
        // Devuelve el body al usuario en flash (útil en dev); en prod muestra mensaje genérico.
        return redirect()->route('checkout.index')->with('error', $msg);

    } catch (\Throwable $e) {
        Log::error('MP REST exception', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
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
