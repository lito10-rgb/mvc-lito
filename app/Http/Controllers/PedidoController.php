<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PedidoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pedidos = Order::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        return view('pedidos.index', compact('pedidos'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $pedido = Order::with('items.product')
                        ->where('user_id', $user->id)
                        ->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }
}
