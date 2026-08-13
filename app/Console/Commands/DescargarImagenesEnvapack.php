<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DescargarImagenesEnvapack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'envapack:descargar-imagenes 
                            {--dry-run : Simular sin hacer cambios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Descarga imágenes de envapack-peru.com y las asigna a los productos';

    /**
     * Mapeo de productos a URLs de envapack
     */
    protected $productoUrls = [
        'Bolsa Fuelle 4 Sellos con/Sin Válvula' => 'https://envapack-peru.com/producto/bolsas-fuelle-4-sellos-con-o-sin-valvula/',
        'Bolsa Fuelle 8 Sellos con/Sin Válvula' => 'https://envapack-peru.com/producto/bolsas-fuelle-8-sellos-con-o-sin-valvula/',
        'Bolsa Doypack con/Sin Válvula' => 'https://envapack-peru.com/producto/bolsas-doypack-con-sin-valvula/',
        'Bolsa Doypack Kraft' => 'https://envapack-peru.com/producto/bolsas-doypack-kraft/',
        'Bolsa Doypack Transparente' => 'https://envapack-peru.com/producto/bolsas-doypack-transparentes/',
        'Bolsas Planas Flexibles' => 'https://envapack-peru.com/producto/bolsas-planas-flexibles/',
        'Bolsas Kraft con Asa' => 'https://envapack-peru.com/producto/bolsas-kraft-con-asa/',
        'Bolsas Kraft SOS' => 'https://envapack-peru.com/producto/bolsas-kraft-sos/',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn("⚠️  MODO DRY-RUN: No se harán cambios reales");
        }

        $descargados = 0;
        $errores = 0;
        $noEncontrados = 0;

        foreach ($this->productoUrls as $tituloProducto => $url) {
            $this->line("Procesando: {$tituloProducto}");
            $this->line("URL: {$url}");

            // Buscar el producto en la base de datos
            $producto = Producto::where('titulo', 'like', '%' . $tituloProducto . '%')->first();

            if (!$producto) {
                $this->line("  ❌ Producto no encontrado en la base de datos");
                $noEncontrados++;
                continue;
            }

            $this->line("  ✅ Producto encontrado: ID {$producto->id}");

            try {
                // Obtener el HTML de la página (sin verificar SSL)
                $response = Http::withoutVerifying()->get($url);
                
                if (!$response->successful()) {
                    $this->line("  ❌ Error al obtener la página: {$response->status()}");
                    $errores++;
                    continue;
                }

                $html = $response->body();

                // Buscar URLs de imágenes en el HTML
                $imagenes = $this->extraerImagenes($html);

                if (empty($imagenes)) {
                    $this->line("  ❌ No se encontraron imágenes en la página");
                    $errores++;
                    continue;
                }

                $this->line("  🖼️  Encontradas " . count($imagenes) . " imágenes");

                // Tomar la primera imagen como portada
                $imagenUrl = $imagenes[0];
                $this->line("  📥 Descargando: {$imagenUrl}");

                if ($dryRun) {
                    $this->line("  📝 [DRY-RUN] Se descargaría y asignaría al producto {$producto->titulo}");
                    $descargados++;
                    continue;
                }

                // Descargar la imagen (sin verificar SSL)
                $imagenResponse = Http::withoutVerifying()->get($imagenUrl);
                
                if (!$imagenResponse->successful()) {
                    $this->line("  ❌ Error al descargar la imagen: {$imagenResponse->status()}");
                    $errores++;
                    continue;
                }

                // Generar nombre único
                $extension = pathinfo(parse_url($imagenUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                $nombreStorage = 'imagenes/productos/envapack/' . Str::uuid() . '.' . $extension;

                // Guardar en storage
                Storage::disk('public')->put($nombreStorage, $imagenResponse->body());

                // Actualizar producto
                $producto->portada = $nombreStorage;
                $producto->save();

                $this->line("  ✅ Imagen guardada: {$nombreStorage}");
                $descargados++;

            } catch (\Exception $e) {
                $this->line("  ❌ Error: " . $e->getMessage());
                $errores++;
            }

            $this->newLine();
        }

        $this->info("📊 RESUMEN:");
        $this->line("✅ Descargados: {$descargados}");
        $this->line("❌ No encontrados: {$noEncontrados}");
        $this->line("⚠️  Errores: {$errores}");
        $this->line("📦 Total procesados: " . count($this->productoUrls));

        return 0;
    }

    /**
     * Extrae URLs de imágenes del HTML
     */
    private function extraerImagenes($html)
    {
        $imagenes = [];

        // Buscar <img> tags
        preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $html, $matches);
        
        if (isset($matches[1])) {
            foreach ($matches[1] as $src) {
                // Convertir URLs relativas a absolutas
                if (strpos($src, 'http') !== 0) {
                    if (strpos($src, '//') === 0) {
                        $src = 'https:' . $src;
                    } else {
                        $src = 'https://envapack-peru.com/' . ltrim($src, '/');
                    }
                }

                // Filtrar imágenes relevantes (evitar iconos, logos, etc.)
                if ($this->esImagenRelevante($src)) {
                    $imagenes[] = $src;
                }
            }
        }

        // Buscar en URLs de fondo
        preg_match_all('/url\([\'"]?([^\'")\)]+)[\'"]?\)/i', $html, $bgMatches);
        
        if (isset($bgMatches[1])) {
            foreach ($bgMatches[1] as $src) {
                if (strpos($src, 'http') !== 0) {
                    if (strpos($src, '//') === 0) {
                        $src = 'https:' . $src;
                    } else {
                        $src = 'https://envapack-peru.com/' . ltrim($src, '/');
                    }
                }

                if ($this->esImagenRelevante($src)) {
                    $imagenes[] = $src;
                }
            }
        }

        return array_unique($imagenes);
    }

    /**
     * Determina si una imagen es relevante para el producto
     */
    private function esImagenRelevante($url)
    {
        // Filtrar URLs que no parecen ser imágenes de producto
        $path = parse_url($url, PHP_URL_PATH);
        
        // Evitar imágenes de iconos, logos, elementos UI
        $patronesIgnorar = [
            'logo', 'icon', 'footer', 'header', 'menu', 'social', 
            'banner', 'slide', 'favicon', 'wp-content', 'themes'
        ];

        foreach ($patronesIgnorar as $patron) {
            if (stripos($path, $patron) !== false) {
                return false;
            }
        }

        // Aceptar solo extensiones de imagen válidas
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }
}