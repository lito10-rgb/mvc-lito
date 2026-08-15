<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LimpiarCacheAgresivo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:limpiar-agresivo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia todos los cachés agresivamente (Laravel, browser, compiled views)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🧹 LIMPIEZA AGRESIVA DE CACHÉ");
        $this->newLine();

        // Limpiar caché de Laravel
        $this->info("📦 Limpiando caché de Laravel...");
        try {
            \Artisan::call('cache:clear');
            $this->line("   ✅ Caché de Laravel limpiado");
        } catch (\Exception $e) {
            $this->line("   ⚠️  Error: " . $e->getMessage());
        }

        // Limpiar caché de configuración
        $this->info("📦 Limpiando caché de configuración...");
        try {
            \Artisan::call('config:clear');
            $this->line("   ✅ Caché de configuración limpiado");
        } catch (\Exception $e) {
            $this->line("   ⚠️  Error: " . $e->getMessage());
        }

        // Limpiar caché de vistas
        $this->info("📦 Limpiando caché de vistas...");
        try {
            \Artisan::call('view:clear');
            $this->line("   ✅ Caché de vistas limpiado");
        } catch (\Exception $e) {
            $this->line("   ⚠️  Error: " . $e->getMessage());
        }

        // Limpiar caché de rutas
        $this->info("📦 Limpiando caché de rutas...");
        try {
            \Artisan::call('route:clear');
            $this->line("   ✅ Caché de rutas limpiado");
        } catch (\Exception $e) {
            $this->line("   ⚠️  Error: " . $e->getMessage());
        }

        // Limpiar caché compilado
        $this->info("📦 Limpiando caché compilado...");
        try {
            \Artisan::call('optimize:clear');
            $this->line("   ✅ Caché compilado limpiado");
        } catch (\Exception $e) {
            $this->line("   ⚠️  Error: " . $e->getMessage());
        }

        // Limpiar directorio bootstrap/cache
        $this->info("📦 Limpiando directorio bootstrap/cache...");
        try {
            $cachePath = base_path('bootstrap/cache');
            if (File::exists($cachePath)) {
                File::cleanDirectory($cachePath);
                $this->line("   ✅ Directorio bootstrap/cache limpiado");
            } else {
                $this->line("   ℹ️  Directorio bootstrap/cache no existe");
            }
        } catch (\Exception $e) {
            $this->line("   ⚠️  Error: " . $e->getMessage());
        }

        // Limpiar directorio storage/framework/cache
        $this->info("📦 Limpiando directorio storage/framework/cache...");
        try {
            $frameworkCachePath = storage_path('framework/cache');
            if (File::exists($frameworkCachePath)) {
                File::cleanDirectory($frameworkCachePath);
                $this->line("   ✅ Directorio storage/framework/cache limpiado");
            } else {
                $this->line("   ℹ️  Directorio storage/framework/cache no existe");
            }
        } catch (\Exception $e) {
            $this->line("   ⚠️  Error: " . $e->getMessage());
        }

        // Limpiar directorio storage/framework/views
        $this->info("📦 Limpiando directorio storage/framework/views...");
        try {
            $viewsCachePath = storage_path('framework/views');
            if (File::exists($viewsCachePath)) {
                File::cleanDirectory($viewsCachePath);
                $this->line("   ✅ Directorio storage/framework/views limpiado");
            } else {
                $this->line("   ℹ️  Directorio storage/framework/views no existe");
            }
        } catch (\Exception $e) {
            $this->line("   ⚠️  Error: " . $e->getMessage());
        }

        $this->newLine();
        $this->info("✅ LIMPIEZA AGRESIVA COMPLETADA");
        $this->newLine();
        $this->info("🔍 PRÓXIMOS PASOS:");
        $this->line("1. Refresca el navegador (Ctrl+F5)");
        $this->line("2. Verifica si los problemas de encoding se resolvieron");
        $this->line("3. Si persiste, borra caché del navegador manualmente");

        return 0;
    }
}