<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CrearServiciosCafe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servicios:crear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear servicios específicos de café para la categoría Nuestros Servicios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔍 Creando servicios de café para Nuestros Servicios...");
        
        $categoriaId = 91; // Nuestros Servicios
        $negocioId = 2; // Cafe Peruano
        
        $servicios = [
            [
                'titulo' => 'Secado de Café Pergamino',
                'titular' => 'Servicio profesional de secado de café pergamino con control de humedad y calidad.',
                'descripcion' => 'Ofrecemos servicio de secado de café pergamino con control preciso de humedad para garantizar la calidad del grano. Utilizamos equipos modernos y seguimos estándares internacionales para mantener las características organolépticas del café.',
                'ruta' => 'secado-cafe-pergamino',
                'precio' => 0,
                'tipo' => 'servicio',
                'stock' => 999,
                'entrega' => 3,
                'portada' => 'productos/portadas/secado-cafe-pergamino.jpg',
                'estado' => 1
            ],
            [
                'titulo' => 'Trillado y Pulido de Café en Pergamino',
                'titular' => 'Servicio de trillado y pulido profesional para café pergamino de alta calidad.',
                'descripcion' => 'Realizamos trillado y pulido de café pergamino con maquinaria especializada para obtener granos de calidad exportación. Proceso controlado para minimizar roturas y mantener la integridad del grano.',
                'ruta' => 'trillado-pulido-cafe-pergamino',
                'precio' => 0,
                'tipo' => 'servicio',
                'stock' => 999,
                'entrega' => 4,
                'portada' => 'productos/portadas/trillado-pulido-cafe-pergamino.jpg',
                'estado' => 1
            ],
            [
                'titulo' => 'Clasificación y Selección de Café',
                'titular' => 'Servicio de clasificación y selección de café según calidad y tamaño.',
                'descripcion' => 'Ofrecemos servicio de clasificación y selección de café utilizando equipos digitales y mano de obra especializada. Clasificamos por tamaño, densidad, color y defectos para garantizar homogeneidad en el producto final.',
                'ruta' => 'clasificacion-seleccion-cafe',
                'precio' => 0,
                'tipo' => 'servicio',
                'stock' => 999,
                'entrega' => 2,
                'portada' => 'productos/portadas/clasificacion-seleccion-cafe.jpg',
                'estado' => 1
            ],
            [
                'titulo' => 'Servicio de Tostado de Café',
                'titular' => 'Servicio de tostado de café artesanal e industrial según perfil requerido.',
                'descripcion' => 'Tostado de café artesanal e industrial con perfiles personalizados según las necesidades del cliente. Utilizamos tostadoras de última generación para obtener tueste uniforme y desarrollo óptimo de aromas y sabores.',
                'ruta' => 'servicio-tostado-cafe',
                'precio' => 0,
                'tipo' => 'servicio',
                'stock' => 999,
                'entrega' => 2,
                'portada' => 'productos/portadas/servicio-tostado-cafe.jpg',
                'estado' => 1
            ],
            [
                'titulo' => 'Servicio de Molido y Envasado de Café',
                'titular' => 'Servicio de molido y envasado de café en diferentes presentaciones.',
                'descripcion' => 'Ofrecemos servicio de molido y envasado de café en diferentes granulometrías y presentaciones. Empacamos en bolsas al vacío con válvula de desgasificado para preservar frescura y aroma del café.',
                'ruta' => 'servicio-molido-envasado-cafe',
                'precio' => 0,
                'tipo' => 'servicio',
                'stock' => 999,
                'entrega' => 2,
                'portada' => 'productos/portadas/servicio-molido-envasado-cafe.jpg',
                'estado' => 1
            ],
            [
                'titulo' => 'Asesoría en Cultivo y Procesos de Beneficiado de Café',
                'titular' => 'Asesoría técnica especializada en cultivo y procesos de beneficiado de café.',
                'descripcion' => 'Brindamos asesoría técnica especializada en cultivo de café, manejo agronómico, cosecha y procesos de beneficiado. Consultoría para mejorar productividad, calidad y sostenibilidad en la producción cafetalera.',
                'ruta' => 'asesoria-cultivo-procesos-beneficiado-cafe',
                'precio' => 0,
                'tipo' => 'servicio',
                'stock' => 999,
                'entrega' => 7,
                'portada' => 'productos/portadas/asesoria-cultivo-cafe.jpg',
                'estado' => 1
            ]
        ];
        
        $creados = 0;
        foreach ($servicios as $servicio) {
            // Verificar si ya existe
            $existente = DB::table('productos')->where('ruta', $servicio['ruta'])->first();
            
            if (!$existente) {
                $productoId = DB::table('productos')->insertGetId([
                    'tipo' => $servicio['tipo'],
                    'ruta' => $servicio['ruta'],
                    'estado' => $servicio['estado'],
                    'titulo' => $servicio['titulo'],
                    'titular' => $servicio['titular'],
                    'descripcion' => $servicio['descripcion'],
                    'multimedia' => json_encode([]),
                    'detalles' => json_encode([]),
                    'precio' => $servicio['precio'],
                    'portada' => $servicio['portada'],
                    'vistas' => 0,
                    'ventas' => 0,
                    'vistasGratis' => 0,
                    'ventasGratis' => 0,
                    'ofertadoPorCategoria' => 0,
                    'ofertadoPorSubCategoria' => 0,
                    'oferta' => 0,
                    'precioOferta' => 0,
                    'descuentoOferta' => 0,
                    'imgOferta' => '',
                    'finOferta' => null,
                    'peso' => 0,
                    'entrega' => $servicio['entrega'],
                    'categoria_id' => $categoriaId,
                    'subcategoria_id' => null,
                    'marca_id' => null,
                    'proveedor_id' => null,
                    'fecha' => now(),
                    'stock' => $servicio['stock'],
                    'costo_envio' => null
                ]);
                
                // Asociar al negocio Cafe Peruano
                DB::table('producto_negocio')->insert([
                    'producto_id' => $productoId,
                    'negocio_id' => $negocioId
                ]);
                
                $this->info("✅ Creado: {$servicio['titulo']}");
                $creados++;
            } else {
                $this->info("⚠️  Ya existe: {$servicio['titulo']}");
            }
        }
        
        $this->newLine();
        $this->info("🎯 COMPLETADO: {$creados} servicios creados en 'Nuestros Servicios'");
        $this->info("📝 Nota: Los servicios tienen precio 0 (Consultar precio) para que tú los edites después");
        
        return 0;
    }
}