<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriasController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PedidoController;
////*
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\User\UserClient;
use App\Http\Controllers\MPTestController;
// kk
use App\Http\Controllers\MPDebugController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'menu'])->name('home');

/* Productos */
Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
Route::get('/productos/buscar', [ProductosController::class, 'buscar'])->name('productos.buscar');
Route::get('/producto/{ruta}', [ProductosController::class, 'mostrarProducto'])->name('producto.mostrar');

/* Categorías / Subcategorías */
Route::prefix('categoria')->name('categoria.')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index');
    Route::get('/{id}', [CategoriaController::class, 'show'])->name('show');
});
Route::get('/subcategoria/{id_categoria}', [SubcategoriasController::class, 'porCategoria']);

/* Carrito */
Route::post('/carrito/agregar/{producto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

/* Admin */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', AdminProductoController::class);
});
Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

/* Producto - vista rápida (admin) */
Route::get('/producto/vista-rapida/{id}', [AdminProductoController::class, 'vistaRapida'])->name('producto.vistaRapida');

/* Favoritos */
Route::post('/favoritos/agregar/{producto}', [FavoritoController::class, 'agregar'])->name('favoritos.agregar');

/* Cotización */
Route::get('/cotizacion/solicitar/{id}', [CotizacionController::class, 'solicitar'])->name('cotizacion.solicitar');

/* Rutas de ejemplo / pruebas */
Route::get('/comprar', function () {
    return 'Página de compra';
})->name('comprar');

/* Auth (registro/login/logout) */
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* Perfil y pedidos (asegúrate de que existan los controladores) */
Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos');

/* Checkout (protegidas por auth) */
Route::middleware('auth')->group(function () {
    // Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout')
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // Alias seguro: ruta nombrada "checkout" que redirige a la principal.
// Esto evita tener que cambiar referencias en distintas partes del código.
Route::get('/checkout-alias', function () {
    return redirect()->route('checkout.index');
})->name('checkout');
    Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

/* PayPal callbacks (pueden ser públicas según tu lógica) */
Route::get('/checkout/paypal/success', [CheckoutController::class, 'paypalSuccess'])->name('checkout.paypal.success');
Route::get('/checkout/paypal/cancel',  [CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');
// *************
Route::get('/mercadopago/success', [CheckoutController::class, 'success'])->name('mercadopago.success');
Route::get('/mercadopago/failure', [CheckoutController::class, 'failure'])->name('mercadopago.failure');
Route::get('/mercadopago/pending', [CheckoutController::class, 'pending'])->name('mercadopago.pending');
Route::post('/mercadopago/notification', [CheckoutController::class, 'mercadoPagoNotification'])->name('mercadopago.notification');
Route::get('/mp/test', function () {
    $token = env('MERCADOPAGO_ACCESS_TOKEN');
    MercadoPagoConfig::setAccessToken($token);
    $client = new UserClient();

    try {
        $user = $client->get(); // consulta /users/me
        return response()->json($user);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/mp/test-preference', [MPTestController::class, 'testPreference']);
Route::post('/mercadopago/notification', [MPTestController::class, 'notification'])->name('mercadopago.notification');

// (opcional) endpoint de prueba que creamos antes
Route::get('/mp/test-preference', [MPTestController::class, 'testPreference']);
