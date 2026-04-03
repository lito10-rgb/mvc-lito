<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
////
// Route::get("/",function(){
//     return view('welcome');
// });


// use App\Http\Controllers\ProductosController;

// use App\Http\Controllers\CategoriasController;

// Route::get('/categorias', [CategoriasController::class, 'index'])->name('categorias.index');

// Route::get('/productos', [ProductosController::class, 'index'])->name('productos.index');
// Route::get('/productos/{id}', [ProductosController::class, 'show'])->name('productos.show');
// <?php

// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubcategoriasController;
use App\Http\Controllers\Admin\ProductoController;
//use App\Http\Controllers\Admin\ProductoController;
Route::get('/', function () {
    return view('home'); // o 'welcome'
})->name('home');

// Ruta del buscador
// Route::get('/buscar', function () {
//     // lógica de búsqueda...
//     return 'Resultados de búsqueda';
// })->name('buscar.productos');
Route::get('/productos/buscar', [ProductosController::class, 'buscar'])->name('productos.buscar');
////lito
// Route::get('/categoria/{id}', [CategoriaController::class, 'show'])->name('categoria.show');
// Route::get('/categoria', [CategoriaController::class, 'index'])->name('categoria.index');
// Route::get('/categoria/{id}', [CategoriaController::class, 'show'])->name('categoria.show');
// Route::get('/categoria/{slug}', [CategoriaController::class, 'show'])->name('categoria.show');
Route::prefix('categoria')->name('categoria.')->group(function () {
Route::get('/', [CategoriaController::class, 'index'])->name('index');
Route::get('/{id}', [CategoriaController::class, 'show'])->name('show');
});
// /Route::get('/subcategoria/{id}', [SubcategoriasController::class, 'show'])->name('subcategoria.show');
// Route::get('/subcategoria/{ruta}', [SubcategoriasController::class, 'show'])->name('subcategoria.show');
// Ruta del carrito (solo como ejemplo temporal)
// Route::prefix('subcategoria')->name('subcategoria.')->group(function () {
//     Route::get('/{id}', [SubcategoriasController::class, 'show'])->name('show');
// });
Route::get('/carrito', function () {
    return 'Carrito de compras';
})->name('carrito.index');
// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::resource('productos', App\Http\Controllers\Admin\ProductoController::class);
// });

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', ProductoController::class);
});

Route::get('/admin', function () {
    return view('admin.dashboard'); // O donde esté tu vista inicial
})->name('admin.dashboard');

/*Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', ProductoController::class);
});*/
//Route::resource('productos', \App\Http\Controllers\Admin\ProductoController::class);
// Route::middleware(['auth'])->prefix('admin')->group(function () {
//     Route::resource('productos', ProductoController::class);
// });
// Route::get('/subcategoria/{ruta}', [SubcategoriasController::class, 'show'])->name('subcategoria.show');
// Route::prefix('admin')->group(function () {
//     Route::get('/', function () {
//         return view('admin.dashboard');
//     });

//     Route::get('/productos', function () {
//         return view('admin.productos.index');
//     });

//     // Aquí agregarás rutas para create, edit, delete, etc.
// });
// Route::get('/test', function () {
//     return view('test');
// });
// Route::get('/subcategorias/{categoria_id}', [App\Http\Controllers\SubcategoriaController::class, 'porCategoria']);
Route::get('/subcategoria/{id_categoria}', [SubcategoriasController::class, 'porCategoria']);
