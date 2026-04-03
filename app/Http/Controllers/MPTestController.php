<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MPTestController extends Controller
{
    // Endpoint: /mp/test-preference
    public function testPreference(Request $request): JsonResponse
    {
        $token = env('MERCADOPAGO_ACCESS_TOKEN');

        if (empty($token)) {
            return response()->json(['ok' => false, 'msg' => 'MERCADOPAGO_ACCESS_TOKEN no configurado'], 500);
        }

        // --- payload mínimo para aislar el problema ---
        $payload = [
            'items' => [
                [
                    'title' => 'Prueba MVC-Lito',
                    'quantity' => 1,
                    'unit_price' => 5.00,        // numeric
                    'currency_id' => 'PEN',      // moneda
                ]
            ],
            // comentar back_urls y notification_url para test mínimo
            //'back_urls' => [
            //    'success' => url('/mercadopago/success'),
            //    'failure' => url('/mercadopago/failure'),
            //    'pending' => url('/mercadopago/pending'),
            //],
            //'auto_return' => 'approved',
            //'notification_url' => url('/mercadopago/notification'),
        ];

        Log::info('MP direct debug payload', ['payload' => $payload]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post('https://api.mercadopago.com/checkout/preferences', $payload);

            // log completo en laravel.log
            Log::info('MP direct debug response', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
            ]);

            return response()->json([
                'ok' => true,
                'http_status' => $response->status(),
                'body_json' => $response->successful() ? $response->json() : null,
                'raw_body' => $response->body(),
                'headers' => $response->headers(),
            ], 200);
        } catch (\Throwable $e) {
            Log::error('MP direct debug exception', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['ok' => false, 'msg' => 'Exception: ' . $e->getMessage()], 500);
        }
    }
}
