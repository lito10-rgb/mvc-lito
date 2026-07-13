<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);
        return view('carrito.index', compact('carrito'));
    }

 public function agregar(Request $request, $id)
{
    $producto = Producto::findOrFail($id);

    $carrito = session()->get('carrito', []);

    if (isset($carrito[$id])) {
        $carrito[$id]['cantidad'] = (int) $carrito[$id]['cantidad'] + 1;
    } else {
        $carrito[$id] = [
            "id"       => $id,
            "titulo"   => $producto->titulo,
            "precio"   => (float) $producto->precio,
            "imagen"   => $producto->portada,
            "ruta"     => $producto->ruta,
            "cantidad" => 1
        ];
    }

    session()->put('carrito', $carrito);

    // Si la petición es AJAX / Fetch, devolver JSON con el nuevo count
    if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
        $count = $this->calcularCantidadTotal($carrito);
        return response()->json([
            'success' => true,
            'message' => 'Producto añadido al carrito.',
            'count' => $count,
            'carrito' => $carrito
        ]);
    }

    // Petición normal: mantén la redirección
    return redirect()->back()->with('success', 'Producto añadido al carrito.');
}


    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }

        return redirect()->back()->with('success', 'Producto eliminado.');
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);

        $carrito = session()->get('carrito', []);
        if (!isset($carrito[$id])) {
            return redirect()->back()->with('error', 'Producto no encontrado en el carrito.');
        }

        $carrito[$id]['cantidad'] = $request->cantidad;
        session()->put('carrito', $carrito);

        return redirect()->back()->with('success', 'Cantidad actualizada.');
    }

    public function vaciar()
    {
        session()->forget('carrito');
        return redirect()->back()->with('success', 'Carrito vaciado.');
    }
    // Devuelve el count total (suma de cantidades)
public function count()
{
    $carrito = session()->get('carrito', []);
    return response()->json(['count' => $this->calcularCantidadTotal($carrito)]);
}

protected function calcularCantidadTotal(array $carrito): int
{
    $sum = 0;
    foreach ($carrito as $item) {
        $sum += (int) ($item['cantidad'] ?? 1);
    }
    return $sum;
}

}
