<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriasController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\FavoritoController;
// lito
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
//use App\Http\Controllers\Admin\ProductoController;
// Route::get('/', function () {
//     return view('home'); // o 'welcome'
// })->name('home');

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');

Route::get('/productos/buscar', [ProductosController::class, 'buscar'])->name('productos.buscar');
Route::get('/producto/{ruta}', [ProductosController::class, 'mostrarProducto'])->name('producto.mostrar');
 // Route::get('/productos/{ruta}', [ProductosController::class, 'mostrarProducto']);
// Route::get('/producto/{ruta}', [ProductosController::class, 'detalle'])->name('producto.detalle');
Route::prefix('categoria')->name('categoria.')->group(function () {
Route::get('/', [CategoriaController::class, 'index'])->name('index');
Route::get('/{id}', [CategoriaController::class, 'show'])->name('show');
});
//////carrito
// Route::get('/carrito', function () {
//     return 'Carrito de compras';
// })->name('carrito.index');

Route::post('/carrito/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

///////////
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', AdminProductoController::class);
});

Route::get('/admin', function () {
    return view('admin.dashboard'); // O donde esté tu vista inicial
})->name('admin.dashboard');

Route::get('/subcategoria/{id_categoria}', [SubcategoriasController::class, 'porCategoria']);
Route::get('/', [HomeController::class, 'menu'])->name('home');
Route::post('/carrito/agregar/{producto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
// Route::get('/producto/vista-rapida/{id}', [ProductoController::class, 'vistaRapida'])->name('producto.vistaRapida');
Route::get('/producto/vista-rapida/{id}', [AdminProductoController::class, 'vistaRapida'])->name('producto.vistaRapida');

Route::post('/favoritos/agregar/{producto}', [FavoritoController::class, 'agregar'])->name('favoritos.agregar');
Route::get('/cotizacion/solicitar/{id}', [CotizacionController::class, 'solicitar'])->name('cotizacion.solicitar');
/////aqui prueba
Route::get('/comprar', function () {
    return 'Página de compra';
})->name('comprar');

Route::get('/productos', function () {
    return 'Listado de productos';
})->name('productos.index');
////////////lito
// Route::get('/productos/{ruta}', [ProductoController::class, 'mostrarProducto']);
// Route::get('/login', function() {
//     return view('auth.login');
// })->name('login');

// Route::get('/register', function() {
//     return view('auth.register');
// });

// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// hhh
Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos');

// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// *************
// use App\Http\Controllers\CheckoutController;

Route::middleware('auth')->group(function () {
    // Mostrar resumen (GET)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

    // Procesar el pago (POST)
    Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');

    // Vista de éxito
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Route::middleware('auth')->group(function () {
//     Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
//     Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
// });


// routes/web.php
// Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout')->middleware('auth');



