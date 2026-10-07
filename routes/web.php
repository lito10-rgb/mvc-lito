<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ComentarioController;
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
Route::get('/ofertas', [ProductosController::class, 'ofertas'])->name('productos.ofertas');
Route::get('/productos/autocomplete', [ProductosController::class, 'autocomplete'])->name('productos.autocomplete');
Route::get('/producto/{ruta}', [ProductosController::class, 'mostrarProducto'])->name('producto.mostrar');
Route::post('/producto/{ruta}/comentar', [ComentarioController::class, 'store'])
    ->middleware('auth')
    ->name('producto.comentar');

/* Categorías / Subcategorías */
Route::prefix('categoria')->name('categoria.')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index');
    Route::get('/{id}', [CategoriaController::class, 'show'])->name('show');
});
Route::get('/subcategoria/{id_categoria}', [SubcategoriasController::class, 'porCategoria']);
Route::get('/subcategoria/show/{ruta}', [SubcategoriasController::class, 'show'])->name('subcategoria.show');

// Ruta específica para Zona Cafetería
Route::get('/zona-cafeteria', function() {
    return app('App\Http\Controllers\CategoriaController')->showByRuta('zona-cafeteria');
})->name('categoria.zona-cafeteria');

// Ruta para Carta del Día
Route::get('/carta-del-dia', [\App\Http\Controllers\ProductosController::class, 'cartaDelDia'])->name('carta.del.dia');
Route::get('/carta-del-dia/pdf', [\App\Http\Controllers\ProductosController::class, 'cartaDelDiaPdf'])->name('carta.del.dia.pdf');

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
    })->name('dashboard')->middleware('permiso:dashboard.ver');

    Route::get('usuarios/asignar', [UserAdminController::class, 'asignarView'])->name('usuarios.asignar.view')->middleware('permiso:usuarios.gestionar');
    Route::post('usuarios/asignar', [UserAdminController::class, 'asignarStore'])->name('usuarios.asignar.store')->middleware('permiso:usuarios.gestionar');

    Route::get('permisos', [\App\Http\Controllers\Admin\PermisoController::class, 'index'])->name('permisos.index')->middleware('permiso:permisos.gestionar');
    Route::get('permisos/buscar-usuarios', [\App\Http\Controllers\Admin\PermisoController::class, 'buscarUsuarios'])->name('permisos.buscarUsuarios')->middleware('permiso:permisos.gestionar');
    Route::get('permisos/rol/{role}/editar', [\App\Http\Controllers\Admin\PermisoController::class, 'editarRol'])->name('permisos.rol.editar')->middleware('permiso:permisos.gestionar');
    Route::put('permisos/rol/{role}', [\App\Http\Controllers\Admin\PermisoController::class, 'guardarRol'])->name('permisos.rol.guardar')->middleware('permiso:permisos.gestionar');
    Route::get('permisos/usuario/{user}/editar', [\App\Http\Controllers\Admin\PermisoController::class, 'editarUsuario'])->name('permisos.usuario.editar')->middleware('permiso:permisos.gestionar');
    Route::put('permisos/usuario/{user}', [\App\Http\Controllers\Admin\PermisoController::class, 'guardarUsuario'])->name('permisos.usuario.guardar')->middleware('permiso:permisos.gestionar');

    Route::get('productos/carta-lista', [AdminProductoController::class, 'cartaLista'])->name('productos.cartaLista')->middleware('permiso:productos.ver');
    Route::resource('productos', AdminProductoController::class)->middleware('permiso:productos.ver');
    Route::post('productos/eliminar-multiple', [AdminProductoController::class, 'eliminarMultiple'])->name('productos.eliminarMultiple')->middleware('permiso:productos.eliminar');
    Route::post('productos/quick-update/{producto}', [AdminProductoController::class, 'quickUpdate'])->name('productos.quickUpdate')->middleware('permiso:productos.editar');
    Route::post('productos/inline-update/{id}', [AdminProductoController::class, 'inlineUpdate'])->name('productos.inlineUpdate')->middleware('permiso:productos.editar');
    Route::post('productos/bulk-update-precio', [AdminProductoController::class, 'bulkUpdatePrecio'])->name('productos.bulkUpdatePrecio')->middleware('permiso:productos.editar');
    Route::post('productos/bulk-update-costo-envio', [AdminProductoController::class, 'bulkUpdateCostoEnvio'])->name('productos.bulkUpdateCostoEnvio')->middleware('permiso:productos.editar');
    Route::post('productos/bulk-update-entrega', [AdminProductoController::class, 'bulkUpdateEntrega'])->name('productos.bulkUpdateEntrega')->middleware('permiso:productos.editar');
Route::post('productos/bulk-update-marca', [AdminProductoController::class, 'bulkUpdateMarca'])->name('productos.bulkUpdateMarca')->middleware('permiso:productos.editar');
Route::post('productos/bulk-update-imagen', [AdminProductoController::class, 'bulkUpdateImagen'])->name('productos.bulkUpdateImagen')->middleware('permiso:productos.editar');
Route::post('productos/bulk-update-carta', [AdminProductoController::class, 'bulkUpdateCarta'])->name('productos.bulkUpdateCarta')->middleware('permiso:productos.editar');
Route::post('productos/bulk-remove-carta', [AdminProductoController::class, 'bulkRemoveCarta'])->name('productos.bulkRemoveCarta')->middleware('permiso:productos.editar');
Route::get('productos/{producto}/duplicar', [AdminProductoController::class, 'duplicar'])->name('productos.duplicar')->middleware('permiso:productos.crear');

    Route::get('ofertas', [\App\Http\Controllers\Admin\OfertasController::class, 'index'])->name('ofertas.index')->middleware('permiso:ofertas.gestionar');
    Route::put('ofertas/{id}', [\App\Http\Controllers\Admin\OfertasController::class, 'update'])->name('ofertas.update')->middleware('permiso:ofertas.gestionar');
    Route::delete('ofertas/{id}/quitar', [\App\Http\Controllers\Admin\OfertasController::class, 'quitarOferta'])->name('ofertas.quitar')->middleware('permiso:ofertas.gestionar');
    Route::post('ofertas/{id}/restaurar', [\App\Http\Controllers\Admin\OfertasController::class, 'restaurarHerencia'])->name('ofertas.restaurar')->middleware('permiso:ofertas.gestionar');
    Route::post('ofertas/restaurar-multiple', [\App\Http\Controllers\Admin\OfertasController::class, 'restaurarHerenciaMultiple'])->name('ofertas.restaurar-multiple')->middleware('permiso:ofertas.gestionar');
    Route::post('ofertas/quitar-multiple', [\App\Http\Controllers\Admin\OfertasController::class, 'quitarOfertaMultiple'])->name('ofertas.quitar-multiple')->middleware('permiso:ofertas.gestionar');
    Route::post('ofertas/{id}/envio-gratis', [\App\Http\Controllers\Admin\OfertasController::class, 'toggleEnvio'])->name('ofertas.envio')->middleware('permiso:ofertas.gestionar');
    Route::post('ofertas/envio-multiple', [\App\Http\Controllers\Admin\OfertasController::class, 'toggleEnvioMultiple'])->name('ofertas.envio-multiple')->middleware('permiso:ofertas.gestionar');

    Route::get('cupones/clientes', [\App\Http\Controllers\Admin\CuponController::class, 'clientes'])->name('cupones.clientes')->middleware('permiso:cupones.gestionar');
    Route::resource('cupones', \App\Http\Controllers\Admin\CuponController::class)->parameters(['cupones' => 'cupon'])->middleware('permiso:cupones.gestionar');
    Route::post('cupones/{cupon}/toggle', [\App\Http\Controllers\Admin\CuponController::class, 'toggle'])->name('cupones.toggle')->middleware('permiso:cupones.gestionar');
    Route::post('cupones/validar', [\App\Http\Controllers\Admin\CuponController::class, 'validar'])->name('cupones.validar')->middleware('permiso:cupones.gestionar');
    Route::post('cupones/{cupon}/enviar', [\App\Http\Controllers\Admin\CuponController::class, 'enviar'])->name('cupones.enviar')->middleware('permiso:cupones.gestionar');

    Route::resource('categorias', \App\Http\Controllers\Admin\CategoriaController::class)->middleware('permiso:categorias.gestionar');
    Route::post('categorias/reorder', [\App\Http\Controllers\Admin\CategoriaController::class, 'reorder'])->name('categorias.reorder')->middleware('permiso:categorias.gestionar');
    Route::resource('subcategorias', \App\Http\Controllers\Admin\SubcategoriaController::class)->middleware('permiso:subcategorias.gestionar');
    Route::post('subcategorias/eliminar-multiple', [\App\Http\Controllers\Admin\SubcategoriaController::class, 'eliminarMultiple'])->name('subcategorias.eliminarMultiple')->middleware('permiso:subcategorias.gestionar');

    Route::resource('proveedores', AdminProveedorController::class)->parameters(['proveedores' => 'proveedor'])->middleware('permiso:proveedores.gestionar');
    Route::post('proveedores/eliminar-multiple', [AdminProveedorController::class, 'eliminarMultiple'])->name('proveedores.eliminarMultiple')->middleware('permiso:proveedores.gestionar');

    // ===== Tareas / Captura (proveedores y productos) =====
    Route::get('tareas', [\App\Http\Controllers\Admin\TareaController::class, 'index'])->name('tareas.index')->middleware('permiso:proveedores.gestionar');
    Route::get('tareas/productos-cotizados', [\App\Http\Controllers\Admin\TareaController::class, 'productosCotizados'])->name('tareas.productosCotizados')->middleware('permiso:proveedores.gestionar');
    Route::post('tareas/productos-hermano', [\App\Http\Controllers\Admin\TareaController::class, 'guardarHermano'])->name('tareas.productosHermano')->middleware('permiso:productos.crear');
    Route::get('tareas/outlook', [\App\Http\Controllers\Admin\TareaController::class, 'outlook'])->name('tareas.outlook')->middleware('permiso:proveedores.gestionar');
    Route::get('tareas/outlook/correo/{idx}', [\App\Http\Controllers\Admin\TareaController::class, 'correoDetalle'])->whereNumber('idx')->name('tareas.outlook.correo')->middleware('permiso:proveedores.gestionar');
    Route::post('tareas/outlook/inscribir', [\App\Http\Controllers\Admin\TareaController::class, 'inscribirProveedor'])->name('tareas.outlook.inscribir')->middleware('permiso:proveedores.gestionar');
    Route::get('tareas/outlook/escribir/{idx}', [\App\Http\Controllers\Admin\TareaController::class, 'escribirCorreo'])->whereNumber('idx')->name('tareas.outlook.escribir')->middleware('permiso:proveedores.gestionar');
    Route::post('tareas/outlook/enviar', [\App\Http\Controllers\Admin\TareaController::class, 'enviarCorreo'])->name('tareas.outlook.enviar')->middleware('permiso:proveedores.gestionar');

    Route::post('tareas/proveedor-web', [\App\Http\Controllers\Admin\TareaController::class, 'inscribirProveedorWeb'])->name('tareas.proveedorWeb')->middleware('permiso:proveedores.gestionar');
    Route::post('tareas/outlook/capturar', [\App\Http\Controllers\Admin\TareaController::class, 'capturarProductos'])->name('tareas.outlook.capturar')->middleware('permiso:productos.crear');
    Route::get('tareas/subcategorias/{categoria}', [\App\Http\Controllers\Admin\TareaController::class, 'subcategoriasDe'])->name('tareas.subcategorias')->middleware('permiso:proveedores.gestionar');
    Route::get('tareas/categorias-json', [\App\Http\Controllers\Admin\TareaController::class, 'categoriasJson'])->name('tareas.categoriasJson')->middleware('permiso:proveedores.gestionar');
    Route::get('tareas/productos/{subcategoria}', [\App\Http\Controllers\Admin\TareaController::class, 'productosDe'])->name('tareas.productos')->middleware('permiso:proveedores.gestionar');
    Route::get('tareas/ultimo-cotizado', [\App\Http\Controllers\Admin\TareaController::class, 'ultimoCotizado'])->name('tareas.ultimoCotizado')->middleware('permiso:proveedores.gestionar');
    Route::get('tareas/clientes', [\App\Http\Controllers\Admin\TareaController::class, 'clientes'])->name('tareas.clientes')->middleware('permiso:cotizaciones.gestionar');

    Route::resource('marcas', AdminMarcaController::class)->middleware('permiso:marcas.gestionar');
    Route::post('marcas/eliminar-multiple', [AdminMarcaController::class, 'eliminarMultiple'])->name('marcas.eliminarMultiple')->middleware('permiso:marcas.gestionar');

    Route::get('catalogos', [\App\Http\Controllers\Admin\CatalogoController::class, 'index'])->name('catalogos.index')->middleware('permiso:catalogos.ver');
    Route::get('catalogos/print', [\App\Http\Controllers\Admin\CatalogoController::class, 'print'])->name('catalogos.print')->middleware('permiso:catalogos.ver');
    Route::get('ficha-tecnica/cafe', [\App\Http\Controllers\Admin\FichaTecnicaController::class, 'cafe'])->name('ficha-tecnica.cafe')->middleware('permiso:catalogos.ver');

    Route::resource('usuarios', UserAdminController::class)->middleware('permiso:usuarios.ver');
    Route::put('usuarios/negocio/bulk', [UserAdminController::class, 'negocioBulk'])->name('usuarios.negocio.bulk')->middleware('permiso:usuarios.gestionar');
    Route::post('usuarios/bulk-correo', [\App\Http\Controllers\Admin\UsuarioCorreoController::class, 'enviar'])->name('usuarios.bulk-correo')->middleware('permiso:usuarios.gestionar');
    Route::get('usuarios/bulk-correo/modal', [\App\Http\Controllers\Admin\UsuarioCorreoController::class, 'modal'])->name('usuarios.bulk-correo.modal')->middleware('permiso:usuarios.gestionar');
    Route::get('usuarios/cambio-password/modal', [\App\Http\Controllers\Admin\UsuarioCorreoController::class, 'cambioPasswordModal'])->name('usuarios.cambio-password.modal')->middleware('permiso:usuarios.gestionar');
    Route::post('usuarios/cambio-password', [\App\Http\Controllers\Admin\UsuarioCorreoController::class, 'enviarAvisoPassword'])->name('usuarios.cambio-password')->middleware('permiso:usuarios.gestionar');
    Route::delete('usuarios/bulk-destroy', [UserAdminController::class, 'destroyBulk'])->name('usuarios.destroy.bulk')->middleware('permiso:usuarios.gestionar');

    // Email Logs
    Route::get('email-logs', [\App\Http\Controllers\Admin\EmailLogController::class, 'index'])->name('email-logs.index')->middleware('permiso:usuarios.gestionar');
    Route::get('email-logs/export', [\App\Http\Controllers\Admin\EmailLogController::class, 'export'])->name('email-logs.export')->middleware('permiso:usuarios.gestionar');
    Route::get('email-logs/export-campaigns', [\App\Http\Controllers\Admin\EmailLogController::class, 'exportCampaigns'])->name('email-logs.export-campaigns')->middleware('permiso:usuarios.gestionar');
    Route::get('mi-perfil', [UserAdminController::class, 'miPerfil'])->name('mi-perfil');
    Route::post('mi-perfil', [UserAdminController::class, 'actualizarMiPerfil'])->name('mi-perfil.update');
    Route::resource('rubros', \App\Http\Controllers\Admin\RubroController::class)->middleware('permiso:rubros.gestionar');
    Route::post('rubros/eliminar-multiple', [\App\Http\Controllers\Admin\RubroController::class, 'eliminarMultiple'])->name('rubros.eliminarMultiple')->middleware('permiso:rubros.gestionar');

    // ===== Ubigeo (Países / Departamentos / Provincias / Distritos) =====
    Route::middleware('permiso:ubigeo.gestionar')->group(function () {
        Route::get('ubigeo/paises', [\App\Http\Controllers\Admin\UbigeoController::class, 'paises'])->name('ubigeo.paises');
        Route::post('ubigeo/paises', [\App\Http\Controllers\Admin\UbigeoController::class, 'paisesStore'])->name('ubigeo.paises.store');
        Route::put('ubigeo/paises/{pais}', [\App\Http\Controllers\Admin\UbigeoController::class, 'paisesUpdate'])->name('ubigeo.paises.update');
        Route::delete('ubigeo/paises/{pais}', [\App\Http\Controllers\Admin\UbigeoController::class, 'paisesDestroy'])->name('ubigeo.paises.destroy');

        Route::get('ubigeo/departamentos', [\App\Http\Controllers\Admin\UbigeoController::class, 'departamentos'])->name('ubigeo.departamentos');
        Route::post('ubigeo/departamentos', [\App\Http\Controllers\Admin\UbigeoController::class, 'departamentosStore'])->name('ubigeo.departamentos.store');
        Route::put('ubigeo/departamentos/{departamento}', [\App\Http\Controllers\Admin\UbigeoController::class, 'departamentosUpdate'])->name('ubigeo.departamentos.update');
        Route::delete('ubigeo/departamentos/{departamento}', [\App\Http\Controllers\Admin\UbigeoController::class, 'departamentosDestroy'])->name('ubigeo.departamentos.destroy');

        Route::get('ubigeo/provincias', [\App\Http\Controllers\Admin\UbigeoController::class, 'provincias'])->name('ubigeo.provincias');
        Route::post('ubigeo/provincias', [\App\Http\Controllers\Admin\UbigeoController::class, 'provinciasStore'])->name('ubigeo.provincias.store');
        Route::put('ubigeo/provincias/{provincia}', [\App\Http\Controllers\Admin\UbigeoController::class, 'provinciasUpdate'])->name('ubigeo.provincias.update');
        Route::delete('ubigeo/provincias/{provincia}', [\App\Http\Controllers\Admin\UbigeoController::class, 'provinciasDestroy'])->name('ubigeo.provincias.destroy');

        Route::get('ubigeo/distritos', [\App\Http\Controllers\Admin\UbigeoController::class, 'distritos'])->name('ubigeo.distritos');
        Route::post('ubigeo/distritos', [\App\Http\Controllers\Admin\UbigeoController::class, 'distritosStore'])->name('ubigeo.distritos.store');
        Route::put('ubigeo/distritos/{distrito}', [\App\Http\Controllers\Admin\UbigeoController::class, 'distritosUpdate'])->name('ubigeo.distritos.update');
        Route::delete('ubigeo/distritos/{distrito}', [\App\Http\Controllers\Admin\UbigeoController::class, 'distritosDestroy'])->name('ubigeo.distritos.destroy');

        // AJAX para cascadas
        Route::get('ubigeo/ajax/departamentos/{pais}', [\App\Http\Controllers\Admin\UbigeoController::class, 'ajaxDepartamentos'])->name('ubigeo.departamentos.ajax');
        Route::get('ubigeo/ajax/provincias/{departamento}', [\App\Http\Controllers\Admin\UbigeoController::class, 'ajaxProvincias'])->name('ubigeo.provincias.ajax');
        Route::get('ubigeo/ajax/distritos/{provincia}', [\App\Http\Controllers\Admin\UbigeoController::class, 'ajaxDistritos'])->name('ubigeo.distritos.ajax');
    });

    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class)->middleware('permiso:posts.gestionar');
    Route::resource('cotizaciones', \App\Http\Controllers\Admin\CotizacionController::class)->middleware('permiso:cotizaciones.gestionar');
    Route::post('cotizaciones/crear-cliente', [\App\Http\Controllers\Admin\CotizacionController::class, 'crearCliente'])->name('cotizaciones.crearCliente')->middleware('permiso:cotizaciones.gestionar');
    Route::get('cotizaciones/{cotizacione}/print', [\App\Http\Controllers\Admin\CotizacionController::class, 'print'])->name('cotizaciones.print')->middleware('permiso:cotizaciones.gestionar');
    Route::post('cotizaciones/{cotizacione}/generar-recibo', [\App\Http\Controllers\Admin\CotizacionController::class, 'generarRecibo'])->name('cotizaciones.generarRecibo')->middleware('permiso:cotizaciones.gestionar');
    Route::get('cotizaciones/{cotizacione}/recibo', [\App\Http\Controllers\Admin\CotizacionController::class, 'verRecibo'])->name('cotizaciones.recibo')->middleware('permiso:cotizaciones.gestionar');
    Route::get('cotizaciones/{cotizacione}/recibo/edit', [\App\Http\Controllers\Admin\CotizacionController::class, 'editarRecibo'])->name('cotizaciones.recibo.edit')->middleware('permiso:cotizaciones.gestionar');
    Route::put('cotizaciones/{cotizacione}/recibo', [\App\Http\Controllers\Admin\CotizacionController::class, 'actualizarRecibo'])->name('cotizaciones.recibo.update')->middleware('permiso:cotizaciones.gestionar');
    Route::resource('visitas-tecnicas', \App\Http\Controllers\Admin\VisitaTecnicaController::class)->only(['index', 'show', 'destroy'])->middleware('permiso:visitas.gestionar');
    Route::resource('suscripciones', \App\Http\Controllers\Admin\SuscripcionController::class)->only(['index', 'destroy'])->middleware('permiso:suscripciones.gestionar');
    Route::resource('pedidos', \App\Http\Controllers\Admin\PedidoController::class)->only(['index', 'show', 'edit', 'update'])->middleware('permiso:pedidos.gestionar');
    Route::resource('condiciones', \App\Http\Controllers\Admin\CondicionesComercialeController::class)->parameters(['condiciones' => 'condicionesComerciale'])->middleware('permiso:condiciones.gestionar');
    Route::resource('negocios', \App\Http\Controllers\Admin\NegocioController::class)->only(['index', 'edit', 'update'])->middleware('permiso:negocios.gestionar');
    Route::resource('negocios.slides', \App\Http\Controllers\Admin\BannerSlideController::class)->except(['show'])->middleware('permiso:negocios.gestionar');
    Route::resource('logos', \App\Http\Controllers\Admin\EmpresaLogoController::class)->middleware('permiso:logos.gestionar');
    Route::resource('plantillas', \App\Http\Controllers\Admin\PlantillaCorreoController::class)->except(['show'])->middleware('permiso:plantillas.gestionar');
    Route::resource('tipos-envio', \App\Http\Controllers\Admin\TipoEnvioController::class)->middleware('permiso:envios.gestionar');
    Route::resource('tarifas-envio', \App\Http\Controllers\Admin\TarifaEnvioController::class)->middleware('permiso:envios.gestionar');
    Route::post('cotizaciones/{cotizacione}/enviar-correo', [\App\Http\Controllers\Admin\CotizacionController::class, 'enviarCorreo'])->name('cotizaciones.enviarCorreo')->middleware('permiso:cotizaciones.gestionar');
    Route::get('cotizaciones/{cotizacione}/duplicar', [\App\Http\Controllers\Admin\CotizacionController::class, 'duplicar'])->name('cotizaciones.duplicar')->middleware('permiso:cotizaciones.gestionar');

    Route::resource('concursos', \App\Http\Controllers\Admin\ConcursoController::class)->except(['edit', 'update'])->middleware('permiso:concursos.gestionar');
    Route::post('concursos/{concurso}/activar', [\App\Http\Controllers\Admin\ConcursoController::class, 'activar'])->name('concursos.activar')->middleware('permiso:concursos.gestionar');
    Route::post('concursos/{concurso}/finalizar', [\App\Http\Controllers\Admin\ConcursoController::class, 'finalizar'])->name('concursos.finalizar')->middleware('permiso:concursos.gestionar');
    Route::post('concursos/{concurso}/generar-participantes', [\App\Http\Controllers\Admin\ConcursoController::class, 'generarParticipantes'])->name('concursos.generarParticipantes')->middleware('permiso:concursos.gestionar');
    Route::post('concursos/{concurso}/enviar-correos', [\App\Http\Controllers\Admin\ConcursoController::class, 'enviarCorreos'])->name('concursos.enviarCorreos')->middleware('permiso:concursos.gestionar');
    Route::post('concursos/{concurso}/reenviar-correos', [\App\Http\Controllers\Admin\ConcursoController::class, 'reenviarCorreos'])->name('concursos.reenviarCorreos')->middleware('permiso:concursos.gestionar');
    Route::post('concursos/{concurso}/declarar-ganador/{participante}', [\App\Http\Controllers\Admin\ConcursoController::class, 'declararGanador'])->name('concursos.declararGanador')->middleware('permiso:concursos.gestionar');
    Route::post('concursos/{concurso}/sorteo-automatico', [\App\Http\Controllers\Admin\ConcursoController::class, 'sorteoAutomatico'])->name('concursos.sorteoAutomatico')->middleware('permiso:concursos.gestionar');
    Route::get('concursos/sorteo/vivo', [\App\Http\Controllers\Admin\ConcursoController::class, 'sorteo'])->name('concursos.sorteo')->middleware('permiso:concursos.gestionar');
    Route::post('concursos/sorteo/validar', [\App\Http\Controllers\Admin\ConcursoController::class, 'validarCodigo'])->name('concursos.validarCodigo')->middleware('permiso:concursos.gestionar');

    Route::prefix('exim')->name('exim.')->middleware('permiso:exim.gestionar')->group(function () {
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
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,60')->name('register.post');
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
Route::middleware(['auth', 'auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/envio/tipos', [CheckoutController::class, 'tiposEnvio'])->name('checkout.tipos');
    Route::post('/checkout/envio', [CheckoutController::class, 'calcularEnvioAjax'])->name('checkout.envio');
    Route::post('/checkout/cupon', [CheckoutController::class, 'aplicarCupon'])->name('checkout.cupon');
    Route::post('/checkout/cupon-quitar', [CheckoutController::class, 'quitarCupon'])->name('checkout.cupon-quitar');

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
Route::get('/negocio/switch/{id}', function ($id) {
    session(['negocio_id' => (int) $id]);
    return redirect()->back();
})->name('negocio.switch');

Route::get('/ubicacion/estados/{pais}', [UbicacionController::class, 'estados']);
Route::get('/ubicacion/provincias/{estado}', [UbicacionController::class, 'provincias']);
Route::get('/ubicacion/distritos/{provincia}', [UbicacionController::class, 'distritos']);
///////////
Route::get('/categoria/{id}/subcategorias', [CategoriaController::class, 'subcategorias']);
//////////

/////lito
Route::get('/paypal/capture', [CheckoutController::class, 'capturePaypal'])
    ->name('paypal.capture');

/*
|--------------------------------------------------------------------------
| Rutas SEO-friendly (compatibilidad con URLs viejas de cafe-peruano.com)
| Se colocan al FINAL para no interferir con rutas existentes.
| Ejemplo: /cafe-tostado-molido-mono-tingales-500gr
|--------------------------------------------------------------------------
*/
Route::get('/empresa-cafe-peruano', function () {
    return redirect()->route('home');
});
Route::get('/tienda', function () {
    return redirect()->route('productos.index');
});
Route::get('/contactenos', function () {
    return redirect()->route('contacto.index');
});
Route::get('/{ruta}', function ($ruta) {
    $negocioId = session('negocio_id');

    // Buscar producto por ruta
    $producto = \App\Models\Producto::where('ruta', $ruta)
        ->when($negocioId, fn($q) => $q->whereHas('negocios', fn($q2) => $q2->where('negocio_id', $negocioId)))
        ->first();
    if ($producto) {
        return app(\App\Http\Controllers\ProductosController::class)->mostrarProducto($ruta);
    }

    // Buscar categoría por ruta
    $categoria = \App\Models\Categoria::where('ruta', $ruta)->first();
    if ($categoria) {
        return app(\App\Http\Controllers\CategoriaController::class)->show($categoria->id);
    }

    // Buscar subcategoría por ruta
    $subcategoria = \App\Models\Subcategoria::where('ruta', $ruta)->first();
    if ($subcategoria) {
        return app(\App\Http\Controllers\SubcategoriasController::class)->show($ruta);
    }

    abort(404);
})->where('ruta', '^[a-zA-Z0-9\-]+$');




