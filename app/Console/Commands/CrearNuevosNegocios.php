<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CrearNuevosNegocios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'negocios:crear-nuevos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear nuevos negocios para avast-peru.com y memoriasusbperu.com';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔍 Creando nuevos negocios para los dominios especificados...");
        
        $nuevosNegocios = [
            [
                'nombre' => 'Avast Peru',
                'dominio' => 'avast-peru.com',
                'empresa' => 'Avast Peru S.A.C.',
                'logo' => 'vistas/img/logos/avast-peru-logo.png',
                'color_primary' => '#6D214F',
                'color_secondary' => '#E63946',
                'color_accent' => '#F1FAEE',
                'color_accent_light' => '#1D3557',
                'nav_tipo' => 'sidebar',
                'color_header_bg' => '#6D214F',
                'nav_btn_color' => '#E63946',
                'nav_btn_texto' => '#FFFFFF',
                'nav_texto' => '#FFFFFF'
            ],
            [
                'nombre' => 'Memorias USB Peru',
                'dominio' => 'memoriasusbperu.com',
                'empresa' => 'Memorias USB Peru S.A.C.',
                'logo' => 'vistas/img/logos/memoriasusbperu-logo.png',
                'color_primary' => '#264653',
                'color_secondary' => '#2A9D8F',
                'color_accent' => '#E9C46A',
                'color_accent_light' => '#F4A261',
                'nav_tipo' => 'topbar',
                'color_header_bg' => '#264653',
                'nav_btn_color' => '#2A9D8F',
                'nav_btn_texto' => '#FFFFFF',
                'nav_texto' => '#FFFFFF'
            ]
        ];
        
        $creados = 0;
        foreach ($nuevosNegocios as $negocio) {
            // Verificar si ya existe por dominio
            $existente = DB::table('negocios')->where('dominio', $negocio['dominio'])->first();
            
            if (!$existente) {
                $negocioId = DB::table('negocios')->insertGetId([
                    'nombre' => $negocio['nombre'],
                    'dominio' => $negocio['dominio'],
                    'empresa' => $negocio['empresa'],
                    'logo' => $negocio['logo'],
                    'color_primary' => $negocio['color_primary'],
                    'color_secondary' => $negocio['color_secondary'],
                    'color_accent' => $negocio['color_accent'],
                    'color_accent_light' => $negocio['color_accent_light'],
                    'nav_tipo' => $negocio['nav_tipo'],
                    'color_header_bg' => $negocio['color_header_bg'],
                    'color_nav_btn' => $negocio['nav_btn_color'],
                    'color_nav_btn_texto' => $negocio['nav_btn_texto'],
                    'color_nav_texto' => $negocio['nav_texto'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $this->info("✅ Negocio creado: {$negocio['nombre']} (ID: {$negocioId})");
                $creados++;
            } else {
                $this->info("⚠️  Negocio ya existe: {$negocio['nombre']} (ID: {$existente->id})");
            }
        }
        
        $this->newLine();
        $this->info("🎯 COMPLETADO: {$creados} nuevos negocios creados");
        $this->info("📝 Nota: Necesitarás configurar los siguientes archivos para cada dominio:");
        $this->line("   - Configuración de GitHub Actions workflows");
        $this->line("   - Configuración de base de datos separada");
        $this->line("   - Configuración de FTP para deployment");
        
        return 0;
    }
}