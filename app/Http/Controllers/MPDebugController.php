<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MPDebugController extends Controller
{
    /**
     * Crea una preference usando la API REST de Mercado Pago (sin SDK)
     * y devuelve el response HTTP crudo para debugging.
     */
    public function debugPreference(Request $request): JsonResponse
    {
        $token = env('MERCADOPAGO_ACCESS_TOKEN');

        if (empty($token)) {
            return response()->json(['ok' => false, 'msg' => 'MERCADOPAGO_ACCESS_TOKEN no configurado'], 500);
        }

        // Payload mínimo para pruebas
        $payload = [
            'items' => [
                [
                    'title' => 'Prueba debug MVC-Lito',
                    'quantity' => 1,
                    'unit_price' => 5.00,
                    'currency_id' => 'PEN',
                ]
            ],
            'back_urls' => [
                'success' => url('/mercadopago/success'),
                'failure' => url('/mercadopago/failure'),
                'pending' => url('/mercadopago/pending'),
            ],
            'auto_return' => 'approved',
            'notification_url' => url('/mercadopago/notification'),
        ];

        Log::info('MP debug payload', ['payload' => $payload]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post('https://api.mercadopago.com/checkout/preferences', $payload);

            // Log completo para laravel.log
            Log::info('MP debug response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
            ]);

            // Devolver todo al navegador para inspección
            return response()->json([
                'ok' => true,
                'http_status' => $response->status(),
                'body' => $response->json(),    // si es JSON, lo decodifica
                'raw_body' => $response->body(),// por si no es JSON o está vacío
                'headers' => $response->headers(),
            ], 200);
        } catch (\Throwable $e) {
            Log::error('MP debug exception', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['ok' => false, 'msg' => 'Exception: ' . $e->getMessage()], 500);
        }
    }
}
