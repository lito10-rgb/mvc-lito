<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = DB::table('productos as p')
    ->leftJoin('categorias as c', 'c.id', '=', 'p.categoria_id')
    ->leftJoin('subcategorias as s', 's.id', '=', 'p.subcategoria_id')
    ->where(function ($q) {
        $q->where('p.oferta', 1)
          ->orWhereNotNull('p.ofertaCategoria')
          ->orWhereNotNull('p.ofertaSubcategoria')
          ->orWhere('p.precioOferta', '>', 0);
    })
    ->select('p.id', 'p.titulo', 'p.ruta', 'p.precio', 'p.precioOferta', 'p.descuentoOferta', 'p.oferta', 'p.ofertaCategoria', 'p.ofertaSubcategoria', 'p.etiquetaOferta', 'p.imgOferta', 'p.finOferta', 'p.ofertadoPorCategoria', 'p.ofertadoPorSubCategoria')
    ->orderBy('p.id')
    ->get();

printf("PRODUCTOS CON OFERTA/DESCUENTO LOCAL: %d\n\n", $rows->count());
foreach ($rows as $r) {
    printf("[%d] %-60s prec=%-8s ofer=%s desc=%s%% etiq=%s\n    ofertaCat=%s ofertaSub=%s finOferta=%s\n",
        $r->id, mb_substr($r->titulo, 0, 58), $r->precio, $r->precioOferta ?: '-', $r->descuentoOferta ?: '-',
        $r->etiquetaOferta ?: '-', $r->ofertaCategoria ?: '-', $r->ofertaSubcategoria ?: '-', $r->finOferta ?: '-');
}