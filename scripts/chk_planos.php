<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = App\Models\Producto::where('ruta', 'planos-para-elaborar-trilladora-morteadora-de-cafe-pergamino')->with('categoria', 'subcategoria')->first();
if (!$p) { echo "NO EXISTE EN LOCAL\n"; exit; }
echo "id={$p->id} titulo={$p->titulo}\n";
echo "precio={$p->precio} oferta={$p->oferta} ofertaCat={$p->ofertaCategoria} ofertaSub={$p->ofertaSubcategoria}\n";
echo "precioOferta={$p->precioOferta} descuentoOferta={$p->descuentoOferta} etiquetaOferta={$p->etiquetaOferta} imgOferta={$p->imgOferta}\n";
echo "stock={$p->stock} peso={$p->peso} entrega={$p->entrega} costo_envio={$p->costo_envio} envio_gratis={$p->envio_gratis}\n";
echo "portada={$p->portada}\n";
echo "categoria={$p->categoria?->nombre} subcategoria={$p->subcategoria?->subcategoria}\n";
echo "detalles=" . substr($p->detalles ?? '', 0, 300) . "\n";