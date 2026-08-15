<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportarSoloProductos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:exportar-solo-productos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta solo productos y sus relaciones (sin cotizaciones ni usuarios)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔍 EXPORTACIÓN DE SOLO PRODUCTOS (SIN COTIZACIONES NI USUARIOS)");
        $this->newLine();

        // Tablas relacionadas con productos que sí exportaremos
        $tablasProductos = [
            'productos',
            'producto_negocio',
            'categorias',
            'categoria_negocio', 
            'subcategorias',
            'subcategoria_negocio',
            'marcas',
            'marca_negocio',
            'proveedores'
        ];

        $this->info("📊 TABLAS A EXPORTAR:");
        foreach ($tablasProductos as $tabla) {
            try {
                $count = DB::table($tabla)->count();
                $this->line("  ✅ {$tabla}: {$count} registros");
            } catch (\Exception $e) {
                $this->line("  ⚠️  {$tabla}: Error al contar");
            }
        }

        $this->newLine();
        $this->info("📋 TABLAS EXCLUIDAS (SEGURIDAD):");
        $this->line("  ❌ cotizaciones (evita problemas de restricciones de clave foránea)");
        $this->line("  ❌ users (evita conflictos de usuarios entre local y servidor)");
        $this->line("  ❌ orders (pedidos reales de producción)");

        $this->newLine();
        $this->info("📦 Exportando solo productos y relaciones...");

        // Generar archivo SQL
        $archivoExport = "solo_productos_" . date('Y_m_d_His') . ".sql";
        $rutaExport = base_path($archivoExport);

        $sql = "-- Exportación de SOLO PRODUCTOS\n";
        $sql .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Base de datos: " . DB::getDatabaseName() . "\n";
        $sql .= "-- Estrategia: INSERT ... ON DUPLICATE KEY UPDATE\n";
        $sql .= "-- Contenido: Productos + Categorías + Marcas + Proveedores + Relaciones\n";
        $sql .= "-- Excluye: Cotizaciones, Usuarios, Pedidos\n\n";

        // Exportar cada tabla de productos
        foreach ($tablasProductos as $tabla) {
            try {
                $count = DB::table($tabla)->count();
                
                if ($count == 0) {
                    $sql .= "-- {$tabla}: 0 registros (saltado)\n\n";
                    continue;
                }

                $sql .= "-- {$tabla}: {$count} registros\n";
                
                $registros = DB::table($tabla)->get();
                
                foreach ($registros as $registro) {
                    $columns = [];
                    $values = [];
                    $updates = [];
                    
                    foreach ($registro as $key => $value) {
                        $columns[] = $key;
                        $values[] = $this->escapeValueSeguro($value);
                        if ($key !== 'id') {
                            $updates[] = "{$key} = " . $this->escapeValueSeguro($value);
                        }
                    }
                    
                    $sql .= "INSERT INTO {$tabla} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ") ";
                    $sql .= "ON DUPLICATE KEY UPDATE " . implode(', ', $updates) . ";\n";
                }
                
                $sql .= "\n";
                
            } catch (\Exception $e) {
                $sql .= "-- {$tabla}: Error al exportar - {$e->getMessage()}\n\n";
            }
        }

        // Escribir archivo
        file_put_contents($rutaExport, $sql);

        if (file_exists($rutaExport)) {
            $this->info("✅ Exportación de productos completada: {$archivoExport}");
            $this->line("   Ubicación: {$rutaExport}");
            $this->line("   Tamaño: " . $this->formatBytes(filesize($rutaExport)));
            
            $this->newLine();
            $this->info("🛡️  SEGURIDAD GARANTIZADA:");
            $this->line("   - Solo productos y relaciones (sin usuarios ni cotizaciones)");
            $this->line("   - Sin restricciones de clave foránea");
            $this->line("   - Producción protegida contra conflictos de usuarios");
            
            $this->newLine();
            $this->info("⚠️  IMPORTANTE:");
            $this->line("   - Importa este archivo en phpMyAdmin del servidor");
            $this->line("   - Luego sube las imágenes de productos");
            $this->line("   - Limpia caché del servidor");
            
            return 0;
        } else {
            $this->error("❌ Error al generar archivo de exportación");
            return 1;
        }
    }

    private function escapeValueSeguro($value)
    {
        if ($value === null) {
            return 'NULL';
        }
        if (is_numeric($value)) {
            return $value;
        }
        
        $value = (string) $value;
        $value = addslashes($value);
        $value = str_replace('"', '\\"', $value);
        
        return "'" . $value . "'";
    }

    private function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}