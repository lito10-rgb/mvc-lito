<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Order::with('user')->withCount('items')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(Order $pedido)
    {
        $pedido->load('items.product', 'user');
        return view('admin.pedidos.show', compact('pedido'));
    }

    public function edit(Order $pedido)
    {
        return view('admin.pedidos.edit', compact('pedido'));
    }

    public function update(Request $request, Order $pedido)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,paid,failed,approved,cancelled,refunded',
        ]);

        $pedido->update($data);

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido #' . $pedido->id . ' actualizado a: ' . $data['status']);
    }
}
