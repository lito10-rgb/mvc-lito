<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NegocioSeeder extends Seeder
{
    public function run()
    {
        DB::table('negocios')->insertOrIgnore([
            ['nombre' => 'Equipos y Maquinas', 'dominio' => 'equiposymaquinas.com', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Cafe Peruano', 'dominio' => 'cafe-peruano.com', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $eqNombres = ['Maquinaria Alimentaria', 'Maquinaria Agrícola', 'Procesamiento de Café',
            'Equipos de Empaque', 'Refrigeración Industrial', 'Insumos Alimentarios',
            'Insumos Agrícolas', 'Repuestos y Accesorios', 'Herramientas Industriales',
            'Módulos de Venta'];
        $cpNombres = ['CAFE ORGANICO', 'CHOCOLATE ORGANICO', 'CAFETERAS Y ACCESORIOS',
            'CURSOS', 'NUESTROS SERVICIOS', 'MAQUINAS PROCESADORAS ',
            'PRODUCTOS AGROINDUSTRIALES', 'PRODUCTOS COMPLEMENTARIOS'];

        DB::statement("INSERT IGNORE INTO categoria_negocio (categoria_id, negocio_id) SELECT id, 1 FROM categorias WHERE nombre IN ('" . implode("','", $eqNombres) . "')");
        DB::statement("INSERT IGNORE INTO categoria_negocio (categoria_id, negocio_id) SELECT id, 2 FROM categorias WHERE nombre IN ('" . implode("','", $cpNombres) . "')");

        DB::statement('INSERT IGNORE INTO subcategoria_negocio (subcategoria_id, negocio_id) SELECT s.id, 1 FROM subcategorias s JOIN categoria_negocio cn ON s.id_categoria = cn.categoria_id WHERE cn.negocio_id = 1');
        DB::statement('INSERT IGNORE INTO subcategoria_negocio (subcategoria_id, negocio_id) SELECT s.id, 2 FROM subcategorias s JOIN categoria_negocio cn ON s.id_categoria = cn.categoria_id WHERE cn.negocio_id = 2');

        DB::statement('INSERT IGNORE INTO producto_negocio (producto_id, negocio_id) SELECT p.id, 1 FROM productos p JOIN categoria_negocio cn ON p.categoria_id = cn.categoria_id WHERE cn.negocio_id = 1');
        DB::statement('INSERT IGNORE INTO producto_negocio (producto_id, negocio_id) SELECT p.id, 2 FROM productos p JOIN categoria_negocio cn ON p.categoria_id = cn.categoria_id WHERE cn.negocio_id = 2');
        DB::statement('INSERT IGNORE INTO producto_negocio (producto_id, negocio_id) SELECT id, 1 FROM productos WHERE id NOT IN (SELECT producto_id FROM producto_negocio)');
    }
}
