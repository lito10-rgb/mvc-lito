<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Models\Producto;
/* PayPal SDK */
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
// use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
///////////lito
// use Srmklive\PayPal\Services\PayPal as PayPalClient;
/* Mercado Pago SDK config (opcional) */
// use MercadoPago\Config as MercadoPagoConfig;
use MercadoPago\Config as MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
class CheckoutController extends Controller
{
    protected function paypalClient()
{
    $clientId = env('PAYPAL_CLIENT_ID');
    $clientSecret = env('PAYPAL_CLIENT_SECRET');

    $environment = new SandboxEnvironment($clientId, $clientSecret);
    return new PayPalHttpClient($environment);
}
    public function __construct()
    {
        // Protege rutas que deben ser autenticadas
        $this->middleware('auth')->only(['index', 'pay']);

        // Configurar token de Mercado Pago si está en .env
        $mpToken = env('MERCADOPAGO_ACCESS_TOKEN');
        if (!empty($mpToken)) {
            try {
                MercadoPagoConfig::setAccessToken($mpToken);
            } catch (\Throwable $e) {
                Log::warning('MP Config warning: ' . $e->getMessage());
            }
        }
    }

    /**
     * Mostrar checkout
     */
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

    /**
     * Procesar pago (mercadopago | paypal | simulado)
     */
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
            default => back()->with('error', 'Método no soportado'),
        };
    }

    protected function simulateSuccess(string $metodo, float $total)
    {
        session()->forget('carrito');
        return redirect()->route('checkout.success')->with('success', "Pago simulado con {$metodo}. Total: {$total}");
    }

    /**
     * Crear preferencia Mercado Pago (via REST)
     */
    protected function crearPreferenciaMercadoPago(array $carrito, float $total)
{
    // Preferir config('services.mercadopago.token') o env
    $token = config('services.mercadopago.token') ?: env('MERCADOPAGO_ACCESS_TOKEN');

    if (empty($token)) {
        Log::error('MP CREATE PREF: token vacío');
        return redirect()->route('checkout.index')->with('error', 'Token de Mercado Pago no configurado.');
    }

    //////
    // Crear orden local (idempotencia)
    $userId = auth()->id() ?: null;
    $order = Order::create([
        'user_id' => $userId,
        'amount' => $total,
        'status' => 'pending',
        'payload' => ['carrito' => $carrito],
    ]);

    // Validar y construir items
    $items = [];
    foreach ($carrito as $p) {
        $qty = max(1, (int) ($p['cantidad'] ?? 1));
        $price = (float) ($p['precio'] ?? 0.0);
        $title = $p['titulo'] ?? $p['nombre'] ?? 'Producto';
        $items[] = [
            'title' => $title,
            'quantity' => $qty,
            'unit_price' => $price,
            'currency_id' => 'PEN',
        ];
    }

    // Añadir envío si corresponde
    $subtotal = array_sum(array_map(fn($i) => $i['unit_price'] * $i['quantity'], $items));
    if ($total > $subtotal) {
        $items[] = [
            'title' => 'Costo de envío',
            'quantity' => 1,
            'unit_price' => round($total - $subtotal, 2),
            'currency_id' => 'PEN',
        ];
    }

    // --- Generar APP URL confiable ---
    $appUrl = config('app.url') ?: env('APP_URL') ?: null;

    if (empty($appUrl)) {
        // fallback a url() (puede devolver http://localhost si no está configurado)
        $backSuccess = url('/checkout/mercadopago/success');
        $backFailure = url('/checkout/mercadopago/failure');
        $backPending = url('/checkout/mercadopago/pending');
        $notificationUrl = url('/checkout/mercadopago/notification');
    } else {
        $base = rtrim($appUrl, '/');
        $backSuccess     = $base . '/checkout/mercadopago/success';
        $backFailure     = $base . '/checkout/mercadopago/failure';
        $backPending     = $base . '/checkout/mercadopago/pending';
        $notificationUrl = $base . '/checkout/mercadopago/notification';
    }

    Log::info('MP CREATE PREF: URLs calculadas', [
        'app_url' => $appUrl,
        'backSuccess' => $backSuccess,
        'backFailure' => $backFailure,
        'backPending' => $backPending,
        'notification' => $notificationUrl,
    ]);

    // Construir payload
    // $payload = [
    //     'items' => $items,
    //     'back_urls' => [
    //         'success' => $backSuccess,
    //         'failure' => $backFailure,
    //         'pending' => $backPending,
    //     ],
    //     'notification_url' => $notificationUrl,
    //     'external_reference' => (string) $order->id,
    // ];
    ////////////lito

    $payload = [
    'items' => $items,

    // 🔥 CLAVE PARA SANDBOX (evita loop)
    'payment_methods' => [
        'excluded_payment_types' => [
            ['id' => 'ticket'],
            ['id' => 'atm'],
        ],
        'installments' => 1,
    ],

    'binary_mode' => true,

    'back_urls' => [
        'success' => $backSuccess,
        'failure' => $backFailure,
        'pending' => $backPending,
    ],

    'notification_url' => $notificationUrl,
    'external_reference' => (string) $order->id,
];

    // Validar back_urls.success antes de incluir auto_return
    if (!empty($backSuccess) && filter_var($backSuccess, FILTER_VALIDATE_URL)) {
        $payload['auto_return'] = 'approved';
    } else {
        Log::error('MP CREATE PREF: back_urls.success inválida o vacía, se aborta (debug).', [
            'backSuccess' => $backSuccess,
            'config_app_url' => config('app.url'),
            'env_APP_URL' => env('APP_URL'),
        ]);

        // Debug inmediato: muestra datos relevantes (quita dd() cuando soluciones)
        // dd([
        //     'error' => 'back_urls.success inválida o vacía',
        //     'backSuccess' => $backSuccess,
        //     'config_app_url' => config('app.url'),
        //     'env_APP_URL' => env('APP_URL'),
        //     'generated_payload' => $payload,
        //     'order_id' => $order->id,
        // ]);
    }

    Log::info('MP CREATE PREF: payload final (antes POST)', ['order_id' => $order->id, 'payload' => $payload]);

    try {
        $resp = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/checkout/preferences', $payload);

        Log::info('MP CREATE PREF: response', ['status' => $resp->status(), 'body' => $resp->body()]);

        if ($resp->successful() && in_array($resp->status(), [200, 201])) {
            $body = $resp->json();
            $preferenceId = $body['id'] ?? null;
            $checkoutUrl = $body['sandbox_init_point'] ?? $body['init_point'] ?? null;

            if ($preferenceId) {
                $order->update([
                    'preference_id' => $preferenceId,
                    'payload' => array_merge($order->payload ?? [], ['preference_raw' => $body]),
                ]);
                session(['mp_preference_id' => $preferenceId]);
            }

            if ($checkoutUrl) {
                return redirect()->away($checkoutUrl);
            }

            return redirect()->route('checkout.index')->with('error', 'MP: no se devolvió URL de checkout. Revisa logs.');
        }

        // Error no successful
        $raw = $resp->body() ?: '';
        $order->update(['status' => 'failed', 'payload' => array_merge($order->payload ?? [], ['mp_error' => $raw])]);
        Log::error('MP CREATE PREF: non-success', ['status' => $resp->status(), 'body' => $raw]);

        // Reintento diagnóstico (solo si body vacío) - no recomendado en prod
        if (empty(trim($raw))) {
            Log::warning('MP CREATE PREF: body vacío, reintento con verify=false (diagnóstico)');
            $resp2 = Http::withOptions(['verify' => false])->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post('https://api.mercadopago.com/checkout/preferences', $payload);

            Log::info('MP CREATE PREF: retry response', ['status' => $resp2->status(), 'body' => $resp2->body()]);

            if ($resp2->successful() && in_array($resp2->status(), [200, 201])) {
                $body2 = $resp2->json();
                $checkoutUrl2 = $body2['sandbox_init_point'] ?? $body2['init_point'] ?? null;
                if ($checkoutUrl2) {
                    $order->update(['preference_id' => $body2['id'] ?? null, 'payload' => array_merge($order->payload ?? [], ['preference_raw' => $body2])]);
                    session(['mp_preference_id' => $body2['id'] ?? null]);
                    return redirect()->away($checkoutUrl2);
                }
            }

            $raw2 = $resp2->body() ?: '';
            $order->update(['status' => 'failed', 'payload' => array_merge($order->payload ?? [], ['mp_error_retry' => $raw2])]);
            return redirect()->route('checkout.index')->with('error', 'Error en Mercado Pago (reintento). Revisar logs.');
        }

        return redirect()->route('checkout.index')->with('error', 'Error en Mercado Pago: ' . substr($raw, 0, 900));
    } catch (\Throwable $e) {
        Log::error('MP CREATE PREF: exception', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        $order->update(['status' => 'failed', 'payload' => array_merge($order->payload ?? [], ['exception' => $e->getMessage()])]);
        if (app()->environment('local')) {
            return redirect()->route('checkout.index')->with('error', 'EXCEPTION MP: ' . $e->getMessage());
        }
        return redirect()->route('checkout.index')->with('error', 'Error conectando con Mercado Pago. Revisa logs.');
    }
}


    /**
     * Crear orden PayPal (sandbox)
     */
   protected function crearOrdenPayPal(array $carrito, float $total)
{
    // 1️⃣ Total en SOLES (interno)
    $totalPen = $total;

    // 2️⃣ Tipo de cambio
    $usdRate = config('payments.usd_rate', 3.75);

    // 3️⃣ Conversión SOLO para PayPal
    $totalUsd = round($totalPen / $usdRate, 2);

    $clientId = env('PAYPAL_CLIENT_ID');
    $clientSecret = env('PAYPAL_CLIENT_SECRET');

    if (empty($clientId) || empty($clientSecret)) {
        return redirect()->route('checkout.index')
            ->with('error', 'Credenciales PayPal no configuradas.');
    }

    $environment = new SandboxEnvironment($clientId, $clientSecret);
    $client = new PayPalHttpClient($environment);

    $request = new OrdersCreateRequest();
    $request->prefer('return=representation');

    $request->body = [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'amount' => [
                'currency_code' => 'USD',
                'value' => number_format($totalUsd, 2, '.', ''),
            ],
            'description' => 'Compra en ' . config('app.name'),
        ]],
        'application_context' => [
            'return_url' => route('paypal.capture'),
            'cancel_url' => route('checkout.index'),
        ],
    ];

    try {
        $response = $client->execute($request);

        foreach ($response->result->links as $link) {
            if ($link->rel === 'approve') {
                return redirect()->away($link->href);
            }
        }

        \Log::error('PayPal approve link no encontrado', [
            'response' => $response
        ]);

        return redirect()->route('checkout.index')
            ->with('error', 'No se pudo iniciar PayPal.');

    } catch (\Exception $e) {
        \Log::error('PayPal create order error', [
            'msg' => $e->getMessage(),
        ]);

        return redirect()->route('checkout.index')
            ->with('error', 'Error al comunicarse con PayPal.');
    }
}


    /**
     * PayPal capture
     */
    public function paypalSuccess(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return redirect()->route('checkout.index')->with('error', 'Token PayPal no recibido.');
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

            // Aquí podrías actualizar la orden local si tienes external_reference
            session()->forget('carrito');
            return redirect()->route('checkout.success')->with('success', 'Pago con PayPal completado (sandbox).');
        } catch (\Exception $e) {
            Log::error('PayPal capture error', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('checkout.index')->with('error', 'Error al capturar pago en PayPal.');
        }
    }

    public function paypalCancel()
    {
        return redirect()->route('checkout.index')->with('error', 'Pago PayPal cancelado por el usuario.');
    }

    /**
     * Callbacks Mercado Pago
     */
    // public function mercadopagoSuccess(Request $request)
    // {
    //     return view('checkout.success', ['data' => $request->all()]);
    // }
    public function mercadoPagoSuccess(Request $request)
{
    $paymentId = $request->get('payment_id');
    $orderId   = $request->get('external_reference');

    $order = Order::findOrFail($orderId);

    // Evitar doble proceso
    if ($order->status === 'paid') {
        return view('checkout.success', compact('order'));
    }

    DB::beginTransaction();

    $order->update([
        'mp_payment_id' => $paymentId,
        'status' => 'paid',
    ]);

    $this->guardarItemsYDescontarStock($order);

    DB::commit();

    session()->forget('carrito');

    return view('checkout.success', compact('order'));
}

    // public function mercadopagoFailure(Request $request)
    // {
    //     Log::warning('MP failure callback', $request->all());
    //     return view('checkout.failure', ['data' => $request->all()]);
    // }
  public function mercadopagoFailure(Request $request)
{
    Log::warning('MP failure callback', $request->all());

    return redirect()->route('checkout.index')
        ->with('error', 'El pago fue cancelado o falló. Intenta nuevamente.');
}


    // public function mercadopagoPending(Request $request)
    // {
    //     Log::info('MP pending callback', $request->all());
    //     return view('checkout.pending', ['data' => $request->all()]);
    // }
public function mercadopagoPending(Request $request)
{
    Log::info('MP pending callback', $request->all());

    return redirect()->route('checkout.index')
        ->with('info', 'Tu pago está pendiente de confirmación. Te avisaremos cuando se apruebe.');
}

    /**
     * Webhook / Notification endpoint (POST)
     */
    // public function mercadopagoNotification(Request $request)
    // {
    //     Log::info('MP notification received', $request->all());
    //     $id = $request->get('id') ?? $request->input('id') ?? null;
    //     if ($id) Log::info('MP notification id', ['id' => $id]);
    //     // Aquí deberías validar y actualizar la orden local según data devuelta por MP
    //     return response()->json(['received' => true], 200);
    // }
public function mercadopagoNotification(Request $request)
{
    Log::info('MP notification received', $request->all());

    // 1️⃣ Solo pagos
    if ($request->get('type') !== 'payment') {
        return response()->json(['ignored' => true], 200);
    }

    $paymentId = $request->get('data')['id'] ?? null;

    if (!$paymentId) {
        Log::warning('MP notification sin payment id');
        return response()->json(['error' => 'No payment id'], 400);
    }

    // 2️⃣ Consultar a Mercado Pago
    \MercadoPago\SDK::setAccessToken(config('services.mercadopago.token'));

    $payment = \MercadoPago\Payment::find_by_id($paymentId);

    if (!$payment) {
        Log::error('MP payment no encontrado', ['id' => $paymentId]);
        return response()->json(['error' => 'Payment not found'], 404);
    }

    // 3️⃣ Solo approved
    if ($payment->status !== 'approved') {
        Log::info('MP payment no aprobado', [
            'id' => $paymentId,
            'status' => $payment->status
        ]);
        return response()->json(['status' => 'not approved'], 200);
    }

    // 4️⃣ Buscar orden local
    $orderId = $payment->external_reference;
    $order = Order::find($orderId);

    if (!$order) {
        Log::error('Orden no encontrada', ['order_id' => $orderId]);
        return response()->json(['error' => 'Order not found'], 404);
    }

    // 5️⃣ Evitar doble proceso
    if ($order->status === 'paid') {
        return response()->json(['status' => 'already processed'], 200);
    }

    DB::beginTransaction();

    try {
        // 6️⃣ Actualizar orden
        $order->update([
            'mp_payment_id' => $paymentId,
            'status' => 'paid',
            'payload' => json_encode($payment),
        ]);

        // 7️⃣ Descontar stock + guardar items
        $this->guardarItemsYDescontarStock($order);

        DB::commit();

        return response()->json(['status' => 'ok'], 200);

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error('Error MP webhook', [
            'payment_id' => $paymentId,
            'error' => $e->getMessage(),
        ]);

        return response()->json(['error' => 'internal error'], 500);
    }
}

    /* ----------------- Helpers ----------------- */

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
    ////////////////lito aqui del paypal
    /* Ruta genérica de éxito (usada por simulate y por algunos retornos)
     * Guarda orden si llegan parámetros de pago.
     */
    public function success(Request $request)
    {
        // Si no hay carrito/usuario puedes usar lo que tengas: aquí guardamos si vienen datos
        $userId = auth()->id() ?: null;

        // Parámetros comunes que pueden venir
        $paymentId = $request->query('payment_id') ?? $request->query('paymentId') ?? null;
        $preferenceId = $request->query('preference_id') ?? $request->query('preferenceId') ?? null;
        $status = $request->query('status') ?? $request->query('collection_status') ?? 'approved';
        $amount = $request->query('transaction_amount') ?? $request->query('amount') ?? null;

        // Guardar order sólo si queremos registrar el intento (opcional)
        try {
            Order::create([
                'user_id'       => $userId,
                'preference_id' => $preferenceId,
                'mp_payment_id' => $paymentId,
                'amount'        => $amount,
                'status'        => $status,
                'payload'       => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
            ]);
        } catch (\Throwable $e) {
            // no rompas la vista por un fallo de guardado, solo loguea
            \Log::warning('Order save en success: '.$e->getMessage());
        }

        // Limpia carrito si corresponde
        session()->forget('carrito');

        // Mostrar vista de confirmación genérica (crea resources/views/checkout/success.blade.php)
        return view('checkout.success', [
            'payment_id' => $paymentId,
            'preference_id' => $preferenceId,
            'status' => $status,
            'request' => $request->all(),
        ]);
    }
////////////////////lito
//  public function capturePaypal(Request $request)
//     {
//     $orderId = $request->query('token'); // PayPal manda el ID aquí
//     if (!$orderId) {
//     return redirect()->route('checkout.index')
//         ->with('error', 'Token PayPal no recibido.');
//     }

//     $userId = auth()->id();

//     // 🔹 Captura real del pago
//     $response = $this->paypalClient()->captureOrder($orderId);

//     if ($response['status'] !== 'COMPLETED') {
//         return redirect()->route('checkout.index')
//             ->with('error', 'Pago PayPal no completado');
//     }

//     // 🔹 Monto real cobrado
//     $amountUSD = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

//     // 🔹 Conversión USD → PEN
//     $usdToPen = $this->tipoCambio(); // ej: 3.75
//     $amountPEN = round($amountUSD * $usdToPen, 2);

//     // 🔹 Guardar orden
//     $order = Order::create([
//         'user_id' => $userId,
//         'gateway' => 'paypal',
//         'reference' => $orderId,
//         'amount' => $amountPEN,
//         'currency' => 'PEN',
//         'status' => 'paid',
//         'payload' => json_encode($response),
//     ]);

//     // 🔹 Guardar productos vendidos + descontar stock
//     $this->guardarItemsYDescontarStock($order);

//     session()->forget('carrito');

//     return view('checkout.success', compact('order'));
// }

public function capturePaypal(Request $request)
{
    // 🔒 1. Validar token
    $orderId = $request->query('token');

    if (!$orderId) {
        return redirect()->route('checkout.index')
            ->with('error', 'Token PayPal inválido');
    }

    // 🔒 2. Evitar doble captura
    if (Order::where('preference_id', $orderId)->exists()) {
        return redirect()->route('checkout.success')
            ->with('success', 'Pago ya procesado');
    }

    // 🔒 3. Verificar carrito
    $carrito = session('carrito', []);
    if (empty($carrito)) {
        return redirect()->route('checkout.index')
            ->with('error', 'Carrito vacío');
    }

    try {
        // 🔒 4. Capturar pago
        $capture = new OrdersCaptureRequest($orderId);
        $capture->prefer('return=representation');

        $response = $this->paypalClient()->execute($capture);

        if (
            !isset($response->result->status) ||
            $response->result->status !== 'COMPLETED'
        ) {
            return redirect()->route('checkout.index')
                ->with('error', 'Pago no completado');
        }

        // 💵 5. Monto USD
        $amountUSD = $response->result
            ->purchase_units[0]
            ->payments
            ->captures[0]
            ->amount
            ->value;

        // 🔄 6. Conversión a PEN
        $rate = config('payments.usd_rate', 3.75);
        $amountPEN = round($amountUSD * $rate, 2);

        DB::beginTransaction();

        // 🧾 7. Guardar orden
        // $order = Order::create([
        //     'user_id'   => auth()->id(),
        //     'gateway'   => 'paypal',
        //     'reference' => $orderId,
        //     'amount'    => $amountPEN,
        //     'currency'  => 'PEN',
        //     'status'    => 'paid',
        //     'payload'   => json_encode($response->result),
        // ]);

        $order = Order::create([
        'user_id'        => auth()->id(),
        'preference_id'  => $orderId, // token de PayPal
        'mp_payment_id'  => $response->result
                                ->purchase_units[0]
                                ->payments
                                ->captures[0]
                                ->id,
        'amount'         => $amountPEN,
        'status'         => 'paid',
        'payload'        => json_encode($response->result),
    ]);






        // 📦 8. Items + stock
        $this->guardarItemsYDescontarStock($order);

        DB::commit();

        // 🧹 9. Limpiar carrito
        session()->forget('carrito');

        return view('checkout.success', compact('order'));

    } catch (\Throwable $e) {

        DB::rollBack();

        \Log::error('PayPal capture error', [
            'order_id' => $orderId,
            'error' => $e->getMessage(),
        ]);
        // dd($e->getMessage(), $e->getTraceAsString(), $request->all());
        return redirect()->route('checkout.index')
            ->with('error', 'Error al procesar el pago');
    }
}

// protected function guardarItemsYDescontarStock(Order $order)
// {
//       $carrito = session('carrito', []);
//     if (empty($carrito)) {
//         return;
//     }
//     foreach (session('carrito', []) as $item) {
//         $product = Producto::find($item['id']);
//         if (!$product) continue;

//         OrderItem::create([
//             'order_id'  => $order->id,
//             'product_id'=> $product->id,
//             'price'     => $item['precio'],
//             'quantity'  => $item['cantidad'],
//         ]);

//         $product->decrement('stock', $item['cantidad']);
//     }
// }
protected function guardarItemsYDescontarStock($order)
    {
        $carrito = session('carrito', []);

        foreach ($carrito as $item) {
            if (!isset($item['id'])) continue; // validar id

            $product = Product::find($item['id']);
            if ($product) {
                // Descontar stock
                $product->stock -= $item['cantidad'] ?? 0;
                $product->save();

                // Guardar detalle en pivot (o tabla items)
                $order->items()->create([
                    'product_id' => $product->id,
                    'cantidad'   => $item['cantidad'] ?? 0,
                    'precio'     => $product->precio,
                ]);
            }
        }
    }
    //////////////hasta aqui capture lito
    public function failure(Request $request)
    {
        \Log::warning('Pago failure callback', $request->all());
        return view('checkout.failure', ['request' => $request->all()]);
    }

    public function pending(Request $request)
    {
        \Log::info('Pago pending callback', $request->all());
        return view('checkout.pending', ['request' => $request->all()]);
    }
    ////////////////
}
