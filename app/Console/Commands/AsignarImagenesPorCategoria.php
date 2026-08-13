<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AsignarImagenesPorCategoria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:asignar-imagenes 
                            {categoria : Nombre de la categoría}
                            {ruta : Ruta local de las imágenes}
                            {--subcarpeta= : Nombre de la subcarpeta dentro de la ruta}
                            {--dry-run : Simular sin hacer cambios}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna imágenes desde una carpeta local a productos por categoría';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $categoriaNombre = $this->argument('categoria');
        $rutaLocal = $this->argument('ruta');
        $subcarpeta = $this->option('subcarpeta');
        $dryRun = $this->option('dry-run');

        $this->info("🔍 Buscando categoría: {$categoriaNombre}");
        
        // Buscar categoría por nombre
        $categoria = Categoria::where('nombre', $categoriaNombre)->first();
        
        if (!$categoria) {
            $this->error("❌ No se encontró la categoría '{$categoriaNombre}'");
            return 1;
        }

        $this->info("✅ Categoría encontrada: ID {$categoria->id}");

        // Buscar productos de la categoría
        $productos = Producto::where('categoria_id', $categoria->id)->get();
        
        if ($productos->isEmpty()) {
            $this->warn("⚠️  No hay productos en esta categoría");
            return 0;
        }

        $this->info("📦 Encontrados {$productos->count()} productos");

        // Ajustar ruta si hay subcarpeta
        if ($subcarpeta) {
            $rutaLocal = $rutaLocal . DIRECTORY_SEPARATOR . $subcarpeta;
            $this->info("📂 Usando subcarpeta: {$subcarpeta}");
        }

        // Verificar que la ruta local existe
        if (!file_exists($rutaLocal)) {
            $this->error("❌ La ruta local no existe: {$rutaLocal}");
            return 1;
        }

        $this->info("📁 Ruta de imágenes: {$rutaLocal}");

        // Listar archivos en la carpeta local
        $archivos = scandir($rutaLocal);
        $archivos = array_filter($archivos, function($archivo) {
            return in_array(strtolower(pathinfo($archivo, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        });

        $this->info("🖼️  Encontrados " . count($archivos) . " archivos de imagen");

        if ($dryRun) {
            $this->warn("⚠️  MODO DRY-RUN: No se harán cambios reales");
        }

        $asignados = 0;
        $noEncontrados = 0;
        $errores = 0;

        foreach ($productos as $producto) {
            $this->line("Procesando: {$producto->titulo} (ID: {$producto->id})");

            // Normalizar el título del producto para comparación
            $tituloNormalizado = $this->normalizarNombre($producto->titulo);
            
            // Buscar imagen que coincida (ignorando mayúsculas, puntos, espacios extra)
            $imagenEncontrada = null;
            
            foreach ($archivos as $archivo) {
                $nombreSinExtension = pathinfo($archivo, PATHINFO_FILENAME);
                $nombreNormalizado = $this->normalizarNombre($nombreSinExtension);
                
                // Comparación flexible
                if ($tituloNormalizado === $nombreNormalizado) {
                    $imagenEncontrada = $archivo;
                    break;
                }
                
                // También intentar con slug
                if (Str::slug($producto->titulo) === Str::slug($nombreSinExtension)) {
                    $imagenEncontrada = $archivo;
                    break;
                }
            }

            if (!$imagenEncontrada) {
                $this->line("  ❌ No se encontró imagen para: {$producto->titulo}");
                $noEncontrados++;
                continue;
            }

            $rutaCompleta = $rutaLocal . DIRECTORY_SEPARATOR . $imagenEncontrada;
            
            if (!file_exists($rutaCompleta)) {
                $this->line("  ❌ El archivo existe en el listado pero no en la ruta: {$imagenEncontrada}");
                $errores++;
                continue;
            }

            $this->line("  ✅ Imagen encontrada: {$imagenEncontrada}");

            if ($dryRun) {
                $this->line("  📝 [DRY-RUN] Se asignaría: {$imagenEncontrada} al producto {$producto->titulo}");
                $asignados++;
                continue;
            }

            try {
                // Leer el archivo
                $contenido = file_get_contents($rutaCompleta);
                
                // Generar nombre único
                $nombreStorage = 'imagenes/productos/' . Str::uuid() . '.' . pathinfo($imagenEncontrada, PATHINFO_EXTENSION);
                
                // Guardar en storage
                Storage::disk('public')->put($nombreStorage, $contenido);
                
                // Actualizar producto
                $producto->portada = $nombreStorage;
                $producto->save();
                
                $this->line("  ✅ Imagen asignada: {$nombreStorage}");
                $asignados++;
                
            } catch (\Exception $e) {
                $this->line("  ❌ Error al procesar: " . $e->getMessage());
                $errores++;
            }
        }

        $this->newLine();
        $this->info("📊 RESUMEN:");
        $this->line("✅ Asignados: {$asignados}");
        $this->line("❌ No encontrados: {$noEncontrados}");
        $this->line("⚠️  Errores: {$errores}");
        $this->line("📦 Total procesados: {$productos->count()}");

        return 0;
    }

    /**
     * Normaliza un nombre para comparación flexible
     * Elimina puntos, barras, espacios extra, normaliza números vs letras, y convierte a mayúsculas
     */
    private function normalizarNombre($nombre)
    {
        // Eliminar puntos
        $nombre = str_replace('.', '', $nombre);
        // Reemplazar barras con espacios
        $nombre = str_replace('/', ' ', $nombre);
        // Normalizar: reemplazar O con 0 (común en errores de digitación)
        $nombre = str_replace('O', '0', $nombre);
        // Eliminar espacios extra
        $nombre = preg_replace('/\s+/', ' ', $nombre);
        // Convertir a mayúsculas para comparación case-insensitive
        $nombre = strtoupper(trim($nombre));
        return $nombre;
    }
}