<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Procesa el retorno de MercadoPago y guarda la orden.
     */
    public function procesarPago(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para continuar.');
        }

        // Parámetros que envía MercadoPago por GET
        $payment_id     = $request->input('payment_id');
        $preference_id  = $request->input('preference_id');
        $status         = $request->input('status');
        $amount         = $request->input('amount'); // tú lo enviarás

        // Guardar payload completo
        $payload = json_encode($request->all(), JSON_UNESCAPED_UNICODE);

        // Guardar la orden en tabla "orders"
        Order::create([
            'user_id'       => $user->id,
            'preference_id' => $preference_id,
            'mp_payment_id' => $payment_id,
            'amount'        => $amount,
            'status'        => $status,
            'payload'       => $payload,
        ]);

        return view('carrito.confirmacion', [
            'status' => $status,
            'payment_id' => $payment_id
        ]);
    }
}
