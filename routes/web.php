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
use App\Http\Controllers\MPTestController;
use App\Http\Controllers\MPDebugController;
use App\Http\Controllers\ContactoController;
////admin
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\SubcategoriaController;
use App\Http\Controllers\Admin\ProveedorController as AdminProveedorController;
use App\Http\Controllers\Admin\MarcaController as AdminMarcaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\BlogController;




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
// *********
// Nueva ruta para obtener solo el count (JSON)
Route::get('/carrito/count', [CarritoController::class, 'count'])->name('carrito.count');

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

/* Perfil y pedidos */

//mod
// Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
Route::middleware(['auth'])->group(function () {

    // Mostrar vista
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');

    // Guardar cambios
    Route::post('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

});
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos');

/* Checkout (protegidas por auth) */
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // Alias (opcional) para compatibilidad con llamadas a route('checkout')
    Route::get('/checkout-alias', function () {
        return redirect()->route('checkout.index');
    })->name('checkout');

    Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
// Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
// Route::get('/checkout/pending', [CheckoutController::class, 'pending'])->name('checkout.pending');

/* PayPal callbacks (públicas por si viene desde PayPal) */
Route::get('/checkout/paypal/success', [CheckoutController::class, 'paypalSuccess'])->name('checkout.paypal.success');
Route::get('/checkout/paypal/cancel',  [CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');

// Mercado Pago callbacks
Route::get('/checkout/mercadopago/success', [CheckoutController::class, 'mercadopagoSuccess'])->name('mercadopago.success');
Route::get('/checkout/mercadopago/failure', [CheckoutController::class, 'mercadopagoFailure'])->name('mercadopago.failure');
Route::get('/checkout/mercadopago/pending', [CheckoutController::class, 'mercadopagoPending'])->name('mercadopago.pending');

// Webhook (POST)
Route::post('/checkout/mercadopago/notification', [CheckoutController::class, 'mercadopagoNotification'])->name('mercadopago.notification');

/* Mme('mercadopago.success');
// Route::get('/merercado Pago back URLs y webhook */
// Route::get('/mercadopago/success', [CheckoutController::class, 'success'])->nacadopago/failure', [CheckoutController::class, 'failure'])->name('mercadopago.failure');
// Route::get('/mercadopago/pending', [CheckoutController::class, 'pending'])->name('mercadopago.pending');
// Route::post('/mercadopago/notification', [CheckoutController::class, 'mercadoPagoNotification'])->name('mercadopago.notification');

/* Endpoints de prueba/debug (moved to dedicated controllers) */
Route::get('/mp/test', [MPTestController::class, 'testUser'])->name('mp.test'); // implementar testUser en MPTestController
Route::get('/mp/test-preference', [MPTestController::class, 'testPreference'])->name('mp.test-preference');
Route::post('/mp/notification-test', [MPTestController::class, 'notification'])->name('mp.notification-test');
// *****
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto.index');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');
// ***********
//Route::post('/productos/eliminar-multiple', [ProductoController::class, 'eliminarMultiple']);
// use App\Http\Controllers\Admin\ProductoController;
// Route::post('/admin/productos/eliminar-multiple', 
//     [AdminProductoController::class, 'eliminarMultiple']
// )->name('admin.productos.eliminarMultiple');
// Route::post('/admin/productos/eliminar-multiple', [AdminProductoController::class, 'eliminarMultiple'])
//     ->name('admin.productos.eliminarMultiple');
Route::post('/admin/productos/eliminar-multiple', 
    [AdminProductoController::class, 'eliminarMultiple']
)->name('admin.productos.eliminarMultiple');
/////lito
Route::prefix('admin')->name('admin.')->group(function () {
    // 🔹 rutas especiales de usuarios (ANTES)
    Route::get('usuarios/asignar', [UserAdminController::class, 'asignarView'])
        ->name('usuarios.asignar.view');

    Route::post('usuarios/asignar', [UserAdminController::class, 'asignarStore'])
        ->name('usuarios.asignar.store');

    Route::resource('productos', AdminProductoController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('subcategorias', SubcategoriaController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('marcas', MarcaController::class);
    ////lito
    Route::resource('usuarios', UserAdminController::class);
    //////lito post
    //  Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    // Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    // Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::resource('posts', PostController::class);
    // RUTAS ADMIN (CRUD)
    // =====================
    // RUTAS PÚBLICAS (BLOG)
    // =====================
    // Route::get('/blog', [BlogController::class, 'index']);
    // Route::get('/post/{slug}', [BlogController::class, 'show']);
    
});
////lito
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
// 7///////////
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('subcategorias', SubcategoriaController::class);

    Route::post('subcategorias/eliminar-multiple', 
        [SubcategoriaController::class, 'eliminarMultiple']
    )->name('subcategorias.eliminarMultiple');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('proveedores', AdminProveedorController::class);
    Route::post('proveedores/eliminar-multiple', [AdminProveedorController::class, 'eliminarMultiple'])
         ->name('proveedores.eliminarMultiple');
});
///
// =====================
// ADMIN - MARCAS
// =====================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('marcas', \App\Http\Controllers\Admin\MarcaController::class);

    // Eliminar múltiples
    Route::post('/marcas/eliminar-multiple', 
        [\App\Http\Controllers\Admin\MarcaController::class, 'eliminarMultiple']
    )->name('marcas.eliminarMultiple');
});
///////////lito
Route::get('/ubicacion/estados/{pais}', [UbicacionController::class, 'estados']);
Route::get('/ubicacion/provincias/{estado}', [UbicacionController::class, 'provincias']);
Route::get('/ubicacion/distritos/{provincia}', [UbicacionController::class, 'distritos']);
///////////
Route::get('/categoria/{id}/subcategorias', [CategoriaController::class, 'subcategorias']);
//////////
Route::get('/mp/confirmacion', [OrderController::class, 'procesarPago'])
    ->name('mp.confirmacion');
/////lito
Route::get('/paypal/capture', [CheckoutController::class, 'capturePaypal'])
    ->name('paypal.capture');


