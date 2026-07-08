<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriasController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\MPTestController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\VisitaTecnicaController;
use App\Http\Controllers\SuscripcionController;
////admin
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\ProveedorController as AdminProveedorController;
use App\Http\Controllers\Admin\MarcaController as AdminMarcaController;
use App\Http\Controllers\UbicacionController;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
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
Route::get('/subcategoria/show/{ruta}', [SubcategoriasController::class, 'show'])->name('subcategoria.show');

/* Carrito */
Route::post('/carrito/agregar/{producto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
// *********
// Nueva ruta para obtener solo el count (JSON)
Route::get('/carrito/count', [CarritoController::class, 'count'])->name('carrito.count');

/* Admin - Auth */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/* Admin - Dashboard (pública dentro de admin, redirige a login si no autenticado) */
Route::get('/producto/vista-rapida/{id}', [AdminProductoController::class, 'vistaRapida'])->name('producto.vistaRapida');

/* Admin - Protegidas (CRUDs) */
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('usuarios/asignar', [UserAdminController::class, 'asignarView'])->name('usuarios.asignar.view');
    Route::post('usuarios/asignar', [UserAdminController::class, 'asignarStore'])->name('usuarios.asignar.store');

    Route::resource('productos', AdminProductoController::class);
    Route::post('productos/eliminar-multiple', [AdminProductoController::class, 'eliminarMultiple'])->name('productos.eliminarMultiple');
    Route::post('productos/quick-update/{producto}', [AdminProductoController::class, 'quickUpdate'])->name('productos.quickUpdate');

    Route::resource('categorias', \App\Http\Controllers\Admin\CategoriaController::class);
    Route::resource('subcategorias', \App\Http\Controllers\Admin\SubcategoriaController::class);
    Route::post('subcategorias/eliminar-multiple', [\App\Http\Controllers\Admin\SubcategoriaController::class, 'eliminarMultiple'])->name('subcategorias.eliminarMultiple');

    Route::resource('proveedores', AdminProveedorController::class);
    Route::post('proveedores/eliminar-multiple', [AdminProveedorController::class, 'eliminarMultiple'])->name('proveedores.eliminarMultiple');

    Route::resource('marcas', AdminMarcaController::class);
    Route::post('marcas/eliminar-multiple', [AdminMarcaController::class, 'eliminarMultiple'])->name('marcas.eliminarMultiple');

    Route::resource('usuarios', UserAdminController::class);
    Route::get('mi-perfil', [UserAdminController::class, 'miPerfil'])->name('mi-perfil');
    Route::post('mi-perfil', [UserAdminController::class, 'actualizarMiPerfil'])->name('mi-perfil.update');
    Route::resource('rubros', \App\Http\Controllers\Admin\RubroController::class);
    Route::post('rubros/eliminar-multiple', [\App\Http\Controllers\Admin\RubroController::class, 'eliminarMultiple'])->name('rubros.eliminarMultiple');
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::resource('cotizaciones', \App\Http\Controllers\Admin\CotizacionController::class);
    Route::resource('visitas-tecnicas', \App\Http\Controllers\Admin\VisitaTecnicaController::class)->only(['index', 'show', 'destroy']);
    Route::resource('suscripciones', \App\Http\Controllers\Admin\SuscripcionController::class)->only(['index', 'destroy']);

    Route::prefix('exim')->name('exim.')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Admin\Exim\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('monedas', \App\Http\Controllers\Admin\Exim\MonedaController::class);
        Route::resource('incoterms', \App\Http\Controllers\Admin\Exim\IncotermController::class);
        Route::resource('transportes', \App\Http\Controllers\Admin\Exim\TransporteController::class);
        Route::resource('seguros', \App\Http\Controllers\Admin\Exim\SeguroController::class);
        Route::resource('pallets', \App\Http\Controllers\Admin\Exim\PalletController::class);
        Route::resource('contenedores', \App\Http\Controllers\Admin\Exim\ContenedorController::class);
        Route::resource('gastos-operativos', \App\Http\Controllers\Admin\Exim\GastoOperativoController::class);
        Route::resource('gastos-logisticos', \App\Http\Controllers\Admin\Exim\GastoLogisticoController::class);
        Route::resource('clientes', \App\Http\Controllers\Admin\Exim\ClienteController::class);
        Route::resource('productos', \App\Http\Controllers\Admin\Exim\ProductoController::class);
        Route::resource('cotizaciones', \App\Http\Controllers\Admin\Exim\CotizacionController::class);
        Route::resource('muestras', \App\Http\Controllers\Admin\Exim\MuestraController::class);
        Route::resource('documentos', \App\Http\Controllers\Admin\Exim\DocumentoController::class);
    });
});

/* Blog / Posts */
Route::get('/post/{slug}', [AdminPostController::class, 'show'])->name('post.show');

/* Favoritos */
Route::post('/favoritos/agregar/{producto}', [FavoritoController::class, 'agregar'])->name('favoritos.agregar');

/* Cotización */
Route::get('/cotizacion/solicitar/{id}', [CotizacionController::class, 'solicitar'])->name('cotizacion.solicitar');
Route::post('/cotizacion/solicitar', [CotizacionController::class, 'store'])->name('cotizacion.store');

/* Visita Técnica */
Route::get('/visita-tecnica', [VisitaTecnicaController::class, 'create'])->name('visita-tecnica.create');
Route::post('/visita-tecnica', [VisitaTecnicaController::class, 'store'])->name('visita-tecnica.store');

/* Boletín Informativo */
Route::post('/boletin/suscribir', [SuscripcionController::class, 'store'])->name('boletin.suscribir');

/* Auth (registro/login/logout) */
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register/edit/{id}', [AuthController::class, 'showEditForm'])->name('register.edit');
Route::post('/register/update/{id}', [AuthController::class, 'updateRegister'])->name('register.update');
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
Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');

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

Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
Route::get('/checkout/pending', [CheckoutController::class, 'pending'])->name('checkout.pending');

/* PayPal callbacks (públicas por si viene desde PayPal) */
Route::get('/checkout/paypal/success', [CheckoutController::class, 'paypalSuccess'])->name('checkout.paypal.success');
Route::get('/checkout/paypal/cancel',  [CheckoutController::class, 'paypalCancel'])->name('checkout.paypal.cancel');

// Mercado Pago callbacks
Route::get('/checkout/mercadopago/success', [CheckoutController::class, 'mercadopagoSuccess'])->name('mercadopago.success');
Route::get('/checkout/mercadopago/failure', [CheckoutController::class, 'mercadopagoFailure'])->name('mercadopago.failure');
Route::get('/checkout/mercadopago/pending', [CheckoutController::class, 'mercadopagoPending'])->name('mercadopago.pending');

// Webhook (POST)
Route::post('/checkout/mercadopago/notification', [CheckoutController::class, 'mercadopagoNotification'])->name('mercadopago.notification');

/* Endpoints de prueba/debug */
Route::get('/mp/test', [MPTestController::class, 'testUser'])->name('mp.test'); // implementar testUser en MPTestController
Route::get('/mp/test-preference', [MPTestController::class, 'testPreference'])->name('mp.test-preference');
Route::post('/mp/notification-test', [MPTestController::class, 'notification'])->name('mp.notification-test');
// *****
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto.index');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
///////////lito
Route::get('/ubicacion/estados/{pais}', [UbicacionController::class, 'estados']);
Route::get('/ubicacion/provincias/{estado}', [UbicacionController::class, 'provincias']);
Route::get('/ubicacion/distritos/{provincia}', [UbicacionController::class, 'distritos']);
///////////
Route::get('/categoria/{id}/subcategorias', [CategoriaController::class, 'subcategorias']);
//////////

/////lito
Route::get('/paypal/capture', [CheckoutController::class, 'capturePaypal'])
    ->name('paypal.capture');


