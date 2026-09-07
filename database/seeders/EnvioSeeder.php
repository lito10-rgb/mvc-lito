<?php

namespace Database\Seeders;

use App\Models\TipoEnvio;
use App\Models\TarifaEnvio;
use App\Models\Categoria;
use Illuminate\Database\Seeder;

class EnvioSeeder extends Seeder
{
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        TarifaEnvio::truncate();
        TipoEnvio::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $local = TipoEnvio::create([
            'nombre' => 'Nacional - Local',
            'slug' => 'nacional-local',
            'descripcion' => 'Envío dentro de Lima Metropolitana',
            'activo' => true,
            'orden' => 1,
        ]);

        $prov = TipoEnvio::create([
            'nombre' => 'Nacional - Provincias',
            'slug' => 'nacional-provincias',
            'descripcion' => 'Envío a provincias dentro del país',
            'activo' => true,
            'orden' => 2,
        ]);

        $intl = TipoEnvio::create([
            'nombre' => 'Internacional',
            'slug' => 'internacional',
            'descripcion' => 'Envío internacional',
            'activo' => true,
            'orden' => 3,
        ]);

        $cafe = Categoria::where('nombre', 'CAFE ORGANICO')->first();
        $choco = Categoria::where('nombre', 'CHOCOLATE ORGANICO')->first();
        $cafeteras = Categoria::where('nombre', 'CAFETERAS Y ACCESORIOS')->first();
        $cafeIds = [$cafe?->id, $choco?->id, $cafeteras?->id];
        $cafeIds = array_filter($cafeIds);

        // Tarifario Nacional-Local: replican el comportamiento actual (general + café por categoría)
        $cafeRanges = [
            [null, 100, 15],
            [100, null, 5],
        ];
        foreach ($cafeIds as $catId) {
            foreach ($cafeRanges as [$min, $max, $costo]) {
                TarifaEnvio::create([
                    'tipo_envio_id' => $local->id,
                    'categoria_id' => $catId,
                    'minimo' => $min,
                    'maximo' => $max,
                    'costo' => $costo,
                    'activo' => true,
                ]);
            }
        }

        // Tarifario general Nacional-Local por subtotal
        $generalRanges = [
            [null, 1000, 25],
            [1000, 1500, 40],
            [1500, 3000, 45],
            [3000, 5000, 95],
            [5000, 9000, 220],
            [9000, 15000, 290],
            [15000, null, 590],
        ];
        foreach ($generalRanges as [$min, $max, $costo]) {
            TarifaEnvio::create([
                'tipo_envio_id' => $local->id,
                'categoria_id' => null,
                'minimo' => $min,
                'maximo' => $max,
                'costo' => $costo,
                'activo' => true,
            ]);
        }

        // Nacional-Provincias (tarifa general más alto)
        $provRanges = [
            [null, 1000, 35],
            [1000, 1500, 55],
            [1500, 3000, 65],
            [3000, 5000, 120],
            [5000, 9000, 260],
            [9000, 15000, 360],
            [15000, null, 740],
        ];
        foreach ($provRanges as [$min, $max, $costo]) {
            TarifaEnvio::create([
                'tipo_envio_id' => $prov->id,
                'categoria_id' => null,
                'minimo' => $min,
                'maximo' => $max,
                'costo' => $costo,
                'activo' => true,
            ]);
        }

        // Internacional (tarifa general)
        $intlRanges = [
            [null, 100, 60],
            [100, 1500, 95],
            [1500, 3000, 130],
            [3000, null, 210],
        ];
        foreach ($intlRanges as [$min, $max, $costo]) {
            TarifaEnvio::create([
                'tipo_envio_id' => $intl->id,
                'categoria_id' => null,
                'minimo' => $min,
                'maximo' => $max,
                'costo' => $costo,
                'activo' => true,
            ]);
        }
    }
}