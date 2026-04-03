@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>Carrito de Compras</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Ejemplo de producto en el carrito -->
            <tr>
                <td>Producto 1</td>
                <td>S/ 100.00</td>
                <td>2</td>
                <td>S/ 200.00</td>
                <td><button class="btn btn-danger btn-sm">Eliminar</button></td>
            </tr>
        </tbody>
    </table>
    <div class="text-end">
        <p><strong>Subtotal:</strong> S/ 200.00</p>
        <p><strong>Impuestos:</strong> S/ 36.00</p>
        <p><strong>Total:</strong> S/ 236.00</p>
        <a href="#" class="btn btn-success">Finalizar compra</a>
    </div>
</div>
@endsection
