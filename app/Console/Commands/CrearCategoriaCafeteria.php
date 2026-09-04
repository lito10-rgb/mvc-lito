<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CrearCategoriaCafeteria extends Command
{
    protected $signature = 'categoria:crear-zona-cafeteria';
    protected $description = 'Crear categoría de Zona Cafetería en Línea para experiencia de café';

    public function handle()
    {
        $this->info("🔍 Creando categoría de Zona Cafetería en Línea...");
        
        // Definir subcategorías
        $subcategorias = [
            'Bebidas de Café',          // Café americano, capuchino, latte, etc. (para delivery)
            'Acompañamientos de Café',  // Tamal, pastel, galleta, sandwiches, etc.
            'Kits de Café',              // Combos para experiencia
            'Suscripciones',            // Café mensual
            'Accesorios Premium',      // Tazas, termos, mugs
            'Regalos de Café',          // Presentaciones para regalo
            'Cafetería en Casa',        // Productos para hacer café en casa
            'Experiencias Virtuales',   // Cursos, talleres
            'Combos Especiales'         // Ofertas y promociones
        ];
        
        // Subcategorías existentes que también deben estar en Zona Cafetería
        $subcategoriasExistentes = [
            'Café Tostado en Grano',   // De Café Orgánico
            'Café Tostado Molido',     // De Café Orgánico
            'Cafeteras',                // De Cafeteras y Accesorios
            'Molinillos',               // De Cafeteras y Accesorios
            'Accesorios para Barista'  // De Cafeteras y Accesorios
        ];
        
        // Verificar si ya existe
        $existente = DB::table('categorias')->where('ruta', 'zona-cafeteria')->first();
        
        if (!$existente) {
            $categoriaId = DB::table('categorias')->insertGetId([
                'categoria' => 'ZONA CAFETERÍA',
                'nombre' => 'Zona Cafetería en Línea',
                'ruta' => 'zona-cafeteria',
                'estado' => 1,
                'oferta' => 0,
                'precioOferta' => 0,
                'descuentoOferta' => 0,
                'imgOferta' => '',
                'finOferta' => '2099-12-31 23:59:59',
                'fecha' => now()
            ]);
            
            $this->info("✅ Categoría creada: Zona Cafetería (ID: {$categoriaId})");
            
            // Asociar al negocio Cafe Peruano (ID 2)
            DB::table('categoria_negocio')->insert([
                'categoria_id' => $categoriaId,
                'negocio_id' => 2
            ]);
            
            $this->info("✅ Categoría asociada al negocio Cafe Peruano");
            
            foreach ($subcategorias as $sub) {
                $subId = DB::table('subcategorias')->insertGetId([
                    'subcategoria' => $sub,
                    'ruta' => Str::slug($sub),
                    'id_categoria' => $categoriaId,
                    'estado' => 1,
                    'ofertadoPorCategoria' => 0,
                    'oferta' => 0,
                    'precioOferta' => 0,
                    'descuentoOferta' => 0,
                    'imgOferta' => '',
                    'finOferta' => '2099-12-31 23:59:59',
                    'detalle' => '',
                    'fecha' => now()
                ]);
                
                DB::table('subcategoria_negocio')->insert([
                    'subcategoria_id' => $subId,
                    'negocio_id' => 2
                ]);
            }
            
            $this->info("✅ " . count($subcategorias) . " subcategorías nuevas creadas");
            
            // Asignar subcategorías existentes que también deben estar en Zona Cafetería
            $subcategoriasExistentes = [
                'Café Tostado en Grano',   // De Café Orgánico
                'Café Tostado Molido',     // De Café Orgánico
                'Cafeteras',                // De Cafeteras y Accesorios
                'Molinillos',               // De Cafeteras y Accesorios
                'Accesorios para Barista'  // De Cafeteras y Accesorios
            ];
            
            $asignadas = 0;
            foreach ($subcategoriasExistentes as $sub) {
                $existente = DB::table('subcategorias')->where('subcategoria', $sub)->first();
                if ($existente) {
                    // Crear duplicado de la subcategoría para esta categoría
                    $nuevoSubId = DB::table('subcategorias')->insertGetId([
                        'subcategoria' => $existente->subcategoria,
                        'ruta' => $existente->ruta . '-zona',
                        'id_categoria' => $categoriaId,
                        'estado' => $existente->estado,
                        'ofertadoPorCategoria' => $existente->ofertadoPorCategoria,
                        'oferta' => $existente->oferta,
                        'precioOferta' => $existente->precioOferta,
                        'descuentoOferta' => $existente->descuentoOferta,
                        'imgOferta' => $existente->imgOferta,
                        'finOferta' => $existente->finOferta,
                        'detalle' => $existente->detalle,
                        'fecha' => now()
                    ]);
                    
                    DB::table('subcategoria_negocio')->insert([
                        'subcategoria_id' => $nuevoSubId,
                        'negocio_id' => 2
                    ]);
                    
                    $asignadas++;
                }
            }
            
            $this->info("✅ " . $asignadas . " subcategorías existentes asignadas (duplicadas para esta categoría)");
            
            $this->info("✅ " . count($subcategorias) . " subcategorías creadas");
            
        } else {
            $this->info("⚠️  La categoría ya existe: {$existente->categoria} (ID: {$existente->id})");
            
            // Actualizar nombre si no coincide
            if ($existente->categoria !== 'ZONA CAFETERÍA') {
                DB::table('categorias')->where('id', $existente->id)->update([
                    'categoria' => 'ZONA CAFETERÍA',
                    'nombre' => 'Zona Cafetería en Línea'
                ]);
                $this->info("✅ Categoría actualizada a: ZONA CAFETERÍA");
                $categoriaId = $existente->id;
            } else {
                $categoriaId = $existente->id;
            }
            
            // Verificar si faltan subcategorías
            $subcategoriasActuales = DB::table('subcategorias')->where('id_categoria', $categoriaId)->count();
            $subcategoriasEsperadas = count($subcategorias);
            
            if ($subcategoriasActuales < $subcategoriasEsperadas) {
                $this->info("⚠️  Faltan subcategorías. Creando...");
                
                // Crear subcategorías faltantes
                foreach ($subcategorias as $sub) {
                    $existenteSub = DB::table('subcategorias')
                        ->where('id_categoria', $categoriaId)
                        ->where('subcategoria', $sub)
                        ->first();
                    
                    if (!$existenteSub) {
                        $subId = DB::table('subcategorias')->insertGetId([
                            'subcategoria' => $sub,
                            'ruta' => Str::slug($sub),
                            'id_categoria' => $categoriaId,
                            'estado' => 1,
                            'ofertadoPorCategoria' => 0,
                            'oferta' => 0,
                            'precioOferta' => 0,
                            'descuentoOferta' => 0,
                            'imgOferta' => '',
                            'finOferta' => '2099-12-31 23:59:59',
                            'detalle' => '',
                            'fecha' => now()
                        ]);
                        
                        DB::table('subcategoria_negocio')->insert([
                            'subcategoria_id' => $subId,
                            'negocio_id' => 2
                        ]);
                        
                        $this->info("✅ Subcategoría creada: {$sub}");
                    }
                }
                
                $this->info("✅ Subcategorías creadas");
            } else {
                $this->info("✅ Todas las subcategorías ya existen");
            }
            
            // Asignar subcategorías existentes que también deben estar en Zona Cafetería
            $asignadas = 0;
            foreach ($subcategoriasExistentes as $sub) {
                $existente = DB::table('subcategorias')->where('subcategoria', $sub)->first();
                if ($existente) {
                    // Verificar si ya existe en esta categoría
                    $yaAsignada = DB::table('subcategorias')
                        ->where('id_categoria', $categoriaId)
                        ->where('subcategoria', $sub)
                        ->first();
                    
                    if (!$yaAsignada) {
                        // Manejar fecha de oferta inválida
                        $finOferta = $existente->finOferta;
                        if ($finOferta == '0000-00-00 00:00:00' || empty($finOferta)) {
                            $finOferta = '2099-12-31 23:59:59';
                        }
                        
                        // Crear duplicado de la subcategoría para esta categoría
                        $nuevoSubId = DB::table('subcategorias')->insertGetId([
                            'subcategoria' => $existente->subcategoria,
                            'ruta' => $existente->ruta . '-zona',
                            'id_categoria' => $categoriaId,
                            'estado' => $existente->estado,
                            'ofertadoPorCategoria' => $existente->ofertadoPorCategoria,
                            'oferta' => $existente->oferta,
                            'precioOferta' => $existente->precioOferta,
                            'descuentoOferta' => $existente->descuentoOferta,
                            'imgOferta' => $existente->imgOferta,
                            'finOferta' => $finOferta,
                            'detalle' => $existente->detalle,
                            'fecha' => now()
                        ]);
                        
                        DB::table('subcategoria_negocio')->insert([
                            'subcategoria_id' => $nuevoSubId,
                            'negocio_id' => 2
                        ]);
                        
                        $asignadas++;
                    }
                }
            }
            
            if ($asignadas > 0) {
                $this->info("✅ " . $asignadas . " subcategorías existentes asignadas (duplicadas para esta categoría)");
            }
        }
        
        $this->newLine();
        $this->info("🎯 COMPLETADO");
        $this->info("📝 Sugerencias para productos de Zona Cafetería en Línea:");
        $this->line("   🎁 KITS DE CAFÉ:");
        $this->line("      - Tipo: 'fisico' - Combos (café + taza + accesorios)");
        $this->line("   📦 SUSCRIPCIONES:");
        $this->line("      - Tipo: 'fisico' - Café mensual recién tostado");
        $this->line("   ☕ ACCESORIOS PREMIUM:");
        $this->line("      - Tipo: 'fisico' - Tazas, termos, mugs de alta calidad");
        $this->line("   🎁 REGALOS DE CAFÉ:");
        $this->line("      - Tipo: 'fisico' - Presentaciones para regalo");
        $this->line("   🏠 CAFETERÍA EN CASA:");
        $this->line("      - Tipo: 'fisico' - Productos para hacer café en casa");
        $this->line("   📚 EXPERIENCIAS VIRTUALES:");
        $this->line("      - Tipo: 'servicio' - Cursos, talleres de café");
        $this->line("   💡 Esta categoría será destacada en el footer con icono");
        
        return 0;
    }
}