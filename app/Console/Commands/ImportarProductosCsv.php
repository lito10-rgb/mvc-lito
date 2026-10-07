<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use App\Models\Cabecera;
use App\Models\Negocio;

class ImportarProductosCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:importar-csv
                            {archivo : Ruta del archivo CSV a importar}
                            {--dry-run : Simular sin insertar}
                            {--imagenes= : Carpeta local con las imágenes (opcional, copia y referencia)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa productos desde un CSV (Excel) hacia la BD local';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $archivo = $this->argument('archivo');
        $dryRun = $this->option('dry-run');
        $carpetaImagenes = $this->option('imagenes');

        if (!file_exists($archivo)) {
            $this->error("❌ Archivo no existe: {$archivo}");
            return 1;
        }

        $this->info("📄 Leyendo archivo: {$archivo}");
        $filas = $this->leerCsv($archivo);

        if (empty($filas)) {
            $this->error("❌ El CSV está vacío o no tiene encabezados");
            return 1;
        }

        if ($dryRun) {
            $this->warn("⚠️  MODO DRY-RUN: No se insertará nada");
        }

        // Cache de resoluciones de categoría/subcategoría/marca/negocio
        $cacheCategorias = [];
        $cacheSubcategorias = [];
        $cacheMarcas = [];
        $cacheNegocios = [];

        $creados = 0;
        $omitidos = 0;
        $errores = 0;
        $bar = $this->output->createProgressBar(count($filas));

        foreach ($filas as $i => $fila) {
            $titulo = trim($fila['titulo'] ?? '');
            if ($titulo === '') {
                $linea = $i + 2;
                $this->line("\n  ⚠️  Fila {$linea} sin título, omitida");
                $omitidos++;
                $bar->advance();
                continue;
            }

            // 1. ¿Ya existe por ruta o título?
            $ruta = trim($fila['ruta'] ?? '');
            if ($ruta === '') {
                $ruta = Str::slug($titulo);
            }
            $ruta = $this->rutaUnica($ruta);
            $fila['ruta'] = $ruta;

            // 2. Resolver categoría
            $catKey = strtolower(trim($fila['categoria'] ?? ''));
            if (!array_key_exists($catKey, $cacheCategorias)) {
                $cacheCategorias[$catKey] = $this->resolverCategoria($fila['categoria'] ?? '');
            }
            $categoria = $cacheCategorias[$catKey];
            if (!$categoria) {
                $catNombre = $fila['categoria'] ?? '';
                $this->line("\n  ❌ Fila " . ($i + 2) . " ({$titulo}): categoría no encontrada '{$catNombre}'");
                $errores++;
                $bar->advance();
                continue;
            }

            // 3. Resolver subcategoría (dentro de la categoría)
            $subKey = strtolower(trim($fila['subcategoria'] ?? '')) . '|' . $categoria->id;
            if (!array_key_exists($subKey, $cacheSubcategorias)) {
                $cacheSubcategorias[$subKey] = $this->resolverSubcategoria($fila['subcategoria'] ?? '', $categoria->id);
            }
            $subcategoria = $cacheSubcategorias[$subKey];
            if (!$subcategoria) {
                $subNombre = $fila['subcategoria'] ?? '';
                $this->line("\n  ❌ Fila " . ($i + 2) . " ({$titulo}): subcategoría no encontrada '{$subNombre}'");
                $errores++;
                $bar->advance();
                continue;
            }

            // 4. Resolver marca (por defecto Mono Tingales id=14)
            $marcaNombre = trim($fila['marca'] ?? '');
            $marcaId = null;
            if ($marcaNombre !== '') {
                $mk = $this->normalizarTexto($marcaNombre);
                if (!array_key_exists($mk, $cacheMarcas)) {
                    $marcaIdBuscada = Marca::all()->first(function ($m) use ($mk) {
                        return $this->normalizarTexto($m->nombre) === $mk || $this->normalizarTexto($m->ruta ?? '') === $mk;
                    });
                    $cacheMarcas[$mk] = $marcaIdBuscada ? $marcaIdBuscada->id : null;
                }
                $marcaId = $cacheMarcas[$mk];
                if (!$marcaId) {
                    $this->line("\n  ❌ Fila " . ($i + 2) . " ({$titulo}): marca no encontrada '{$marcaNombre}'");
                    $errores++;
                    $bar->advance();
                    continue;
                }
            }
            $marcaId = $marcaId ?? 14;

            // 5. Negocios (por defecto Cafe Peruano id=2)
            $negocios = [];
            $negociosRaw = trim($fila['negocios'] ?? '');
            if ($negociosRaw === '') {
                $negociosRaw = 'Cafe Peruano';
            }
            foreach (explode('|', $negociosRaw) as $nombreNeg) {
                $nombreNeg = trim($nombreNeg);
                if ($nombreNeg === '') continue;
                $nk = $this->normalizarTexto($nombreNeg);
                if (!array_key_exists($nk, $cacheNegocios)) {
                    $cacheNegocios[$nk] = Negocio::all()->first(function ($n) use ($nk) {
                        return $this->normalizarTexto($n->nombre) === $nk;
                    })?->id;
                }
                $nid = $cacheNegocios[$nk];
                if ($nid) $negocios[] = $nid;
            }

            $precio = $this->toNumero($fila['precio'] ?? 0);
            $precioOferta = $this->toNumero($fila['precioOferta'] ?? 0);
            $peso = ($fila['peso'] ?? '') !== '' ? $this->toNumero($fila['peso']) : null;
            $entrega = $this->toEntrega($fila['entrega'] ?? '');
            $tipo = in_array(trim($fila['tipo'] ?? ''), ['fisico', 'no_fisico', 'servicio']) ? trim($fila['tipo']) : 'fisico';
            $estado = (int) ($fila['estado'] ?? 1) === 1 ? 1 : 0;
            $stock = (int) ($fila['stock'] ?? 0);
            $costoEnvio = ($fila['costo_envio'] ?? '') !== '' ? $this->toNumero($fila['costo_envio']) : null;
            $envioGratis = (int) ($fila['envio_gratis'] ?? 0) === 1 ? 1 : 0;

            $titular = trim($fila['titular'] ?? '');
            if ($titular === '') $titular = $titulo;
            $descripcion = $fila['descripcion'] ?? '';

            $detalles = trim($fila['detalles'] ?? '');
            if ($detalles !== '' && !str_starts_with($detalles, '{')) {
                // Formato clave:valor separado por ; -> JSON
                $arr = [];
                foreach (explode(';', $detalles) as $par) {
                    $par = trim($par);
                    if ($par === '') continue;
                    [$k, $v] = array_pad(explode(':', $par, 2), 2, '');
                    $arr[trim($k)] = trim($v) === '' ? [] : explode(',', trim($v));
                }
                $detalles = json_encode($arr, JSON_UNESCAPED_UNICODE);
            }

            // Portada
            $portada = $this->resolverPortada(trim($fila['portada'] ?? ''), $ruta, $carpetaImagenes, $dryRun);

            // Multimedia (json o lista ;)
            $multimedia = $this->resolverMultimedia(trim($fila['multimedia'] ?? ''), $portada, $carpetaImagenes, $ruta, $dryRun);

            $palabras = $fila['palabras_claves'] ?? $titulo;

            if ($dryRun) {
                $this->line("\n  📝 [DRY-RUN] Crearía: {$titulo} | precio S/{$precio} | {$fila['categoria']} > {$fila['subcategoria']} | ruta={$ruta} | portada={$portada}");
                $creados++;
                $bar->advance();
                continue;
            }

            try {
                DB::transaction(function () use ($titulo, $titular, $descripcion, $multimedia, $detalles, $precio, $precioOferta, $peso, $entrega, $tipo, $estado, $stock, $costoEnvio, $envioGratis, $ruta, $portada, $categoria, $subcategoria, $marcaId, $negocios, $palabras) {
                    $producto = Producto::create([
                        'tipo' => $tipo,
                        'ruta' => $ruta,
                        'estado' => $estado,
                        'titulo' => $titulo,
                        'titular' => $titular,
                        'descripcion' => $descripcion,
                        'multimedia' => $multimedia,
                        'detalles' => $detalles,
                        'precio' => $precio,
                        'portada' => $portada,
                        'vistas' => rand(10, 500),
                        'ventas' => rand(1, 100),
                        'vistasGratis' => 0,
                        'ventasGratis' => 0,
                        'ofertadoPorCategoria' => 0,
                        'ofertadoPorSubCategoria' => 0,
                        'oferta' => 0,
                        'precioOferta' => $precioOferta,
                        'descuentoOferta' => 0,
                        'imgOferta' => null,
                        'finOferta' => null,
                        'peso' => $peso,
                        'entrega' => $entrega,
                        'categoria_id' => $categoria->id,
                        'subcategoria_id' => $subcategoria->id,
                        'marca_id' => $marcaId,
                        'proveedor_id' => null,
                        'fecha' => now(),
                        'stock' => $stock,
                        'costo_envio' => $costoEnvio,
                        'envio_gratis' => $envioGratis,
                    ]);

                    // Negocios
                    if (!empty($negocios)) {
                        $producto->negocios()->sync($negocios);
                    }

                    // Cabecera SEO
                    Cabecera::create([
                        'ruta' => $ruta,
                        'titulo' => $titulo,
                        'descripcion' => mb_substr($descripcion, 0, 250),
                        'palabras_claves' => $palabras,
                        'portada' => $portada,
                        'fecha' => now(),
                    ]);
                });
                $creados++;
            } catch (\Exception $e) {
                $this->line("\n  ❌ Fila " . ($i + 2) . " ({$titulo}): " . $e->getMessage());
                $errores++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("📊 RESUMEN:");
        $this->line("✅ Productos creados/planificados: {$creados}");
        $this->line("⏭️  Omitidos: {$omitidos}");
        $this->line("❌ Errores: {$errores}");

        if (!$dryRun) {
            $this->newLine();
            $this->info("🚀 Para subir a la página (remoto):");
            $this->line("   1. php artisan sync:exportar-solo-productos");
            $this->line("   2. Importa el .sql en phpMyAdmin del servidor");
            $this->line("   3. Sube las imágenes por cPanel/FTP");
            $this->line("   4. Limpia caché remoto");
        }

        return $errores > 0 ? 1 : 0;
    }

    /**
     * Lee un CSV (con BOM UTF-8) asumiendo encabezados en la primera fila.
     */
    private function leerCsv($archivo)
    {
        $contenido = file_get_contents($archivo);
        // Quitar BOM UTF-8
        if (str_starts_with($contenido, "\xEF\xBB\xBF")) {
            $contenido = substr($contenido, 3);
        }
        $lineas = explode("\n", $contenido);
        $lineas = array_map('rtrim', $lineas);
        $lineas = array_values(array_filter($lineas, function ($l) {
            return trim($l) !== '';
        }));

        if (empty($lineas)) return [];

        $delimitador = $this->detectarDelimitador($lineas[0]);
        $encabezados = str_getcsv($lineas[0], $delimitador);
        // Normalizar encabezados: sin acentos, minúsculas, espacios -> _
        $encabezados = array_map(function ($h) {
            $h = trim($h);
            $h = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', ' '], ['a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', '_'], $h);
            return $h;
        }, $encabezados);

        $filas = [];
        for ($i = 1; $i < count($lineas); $i++) {
            $campos = str_getcsv($lineas[$i], $delimitador);
            $fila = [];
            foreach ($encabezados as $j => $header) {
                $fila[$header] = $campos[$j] ?? '';
            }
            $filas[] = $fila;
        }
        return $filas;
    }

    private function detectarDelimitador($linea)
    {
        $comas = substr_count($linea, ',');
        $puntosComa = substr_count($linea, ';');
        return $puntosComa > $comas ? ';' : ',';
    }

    private function resolverCategoria($nombre)
    {
        $nombre = trim($nombre);
        if ($nombre === '') return null;
        $norm = $this->normalizarTexto($nombre);
        $cat = Categoria::all()->first(function ($c) use ($norm) {
            return $this->normalizarTexto($c->nombre) === $norm || $this->normalizarTexto($c->ruta ?? '') === $norm;
        });
        return $cat;
    }

    private function resolverSubcategoria($nombre, $categoriaId)
    {
        $nombre = trim($nombre);
        if ($nombre === '') return null;
        $norm = $this->normalizarTexto($nombre);
        $sub = Subcategoria::where('id_categoria', $categoriaId)->get()->first(function ($s) use ($norm) {
            return $this->normalizarTexto($s->subcategoria) === $norm || $this->normalizarTexto($s->ruta ?? '') === $norm;
        });
        return $sub;
    }

    private function normalizarTexto($texto)
    {
        $texto = trim((string) $texto);
        $texto = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ü', 'Ü'],
            ['a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', 'u', 'U'],
            $texto
        );
        return preg_replace('/\s+/', ' ', strtolower($texto));
    }

    private function rutaUnica($ruta)
    {
        $candidata = $ruta;
        $i = 2;
        while (Producto::where('ruta', $candidata)->exists()) {
            $candidata = $ruta . '-' . $i;
            $i++;
        }
        return $candidata;
    }

    private function toNumero($valor)
    {
        $valor = trim((string) $valor);
        if ($valor === '') return 0;
        $valor = str_replace(['S/', 's/', 'S', '$', ' '], '', $valor);
        $valor = str_replace([',', '.'], ['', ''], $valor);
        return (float) $valor;
    }

    private function toEntrega($valor)
    {
        $valor = trim((string) $valor);
        if ($valor === '') return 0;
        $valorBajo = strtolower($valor);
        // "Entrega inmediata" -> 0 días
        if (str_contains($valorBajo, 'inmediat')) return 0;
        // "Por encargo — 1 días (aprox)" -> extraer número de días
        if (str_contains($valorBajo, 'encargo') || str_contains($valorBajo, 'dias') || str_contains($valorBajo, 'día')) {
            if (preg_match('/(\d+)/', $valor, $m)) {
                return (int) $m[1];
            }
            return 1;
        }
        // Número puro
        if (is_numeric($valor)) return (int) $valor;
        return 0;
    }

    private function resolverPortada($portada, $ruta, $carpetaImagenes, $dryRun)
    {
        $extensiones = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $nombreBase = $portada !== '' ? $portada : $ruta;

        // Patrón 1: ya viene con formato completo productos/portadas/x.jpg
        if ($portada !== '' && ($this->existePortada($portada) || $this->existeEnCarpeta($portada, $carpetaImagenes))) {
            return $this->guardarPortada($portada, $carpetaImagenes, $dryRun);
        }

        // Patrón 2: buscar producto/portadas/{slug}.ext
        foreach ($extensiones as $ext) {
            $candidata = "productos/portadas/{$nombreBase}.{$ext}";
            if ($this->existePortada($candidata)) {
                return $candidata;
            }
            if ($carpetaImagenes && $this->existeEnCarpeta($nombreBase . '.' . $ext, $carpetaImagenes)) {
                return $this->guardarPortada($nombreBase . '.' . $ext, $carpetaImagenes, $dryRun);
            }
        }

        return 'defaults/default-portada.jpg';
    }

    private function existePortada($ruta)
    {
        return Storage::disk('public')->exists($ruta);
    }

    private function existeEnCarpeta($archivo, $carpeta)
    {
        if (!$carpeta) return false;
        return file_exists(rtrim($carpeta, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $archivo);
    }

    private function guardarPortada($archivo, $carpeta, $dryRun)
    {
        if (!$carpeta) return $archivo;

        $origen = rtrim($carpeta, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($archivo);
        if (!file_exists($origen)) return $archivo;

        if ($dryRun) return $archivo;

        $destino = 'productos/portadas/' . basename($archivo);
        if (!Storage::disk('public')->exists($destino)) {
            Storage::disk('public')->put($destino, file_get_contents($origen));
        }
        return $destino;
    }

    private function resolverMultimedia($multimedia, $portada, $carpetaImagenes, $ruta, $dryRun)
    {
        $lista = [];
        if ($multimedia !== '') {
            // Formato: lista separada por ; o JSON
            if (str_starts_with($multimedia, '[')) {
                return $multimedia;
            }
            $nombres = explode(';', $multimedia);
            foreach ($nombres as $nombre) {
                $nombre = trim($nombre);
                if ($nombre === '') continue;
                $rutaMult = $this->resolverPortada($nombre, $ruta, $carpetaImagenes, $dryRun);
                if ($rutaMult !== 'defaults/default-portada.jpg') {
                    $lista[] = $rutaMult;
                }
            }
        }
        // Si no hay multimedia pero sí portada, usarías la misma como galería
        if (empty($lista) && $portada !== 'defaults/default-portada.jpg') {
            $lista[] = $portada;
        }
        return $lista ? json_encode($lista) : '[]';
    }
}