<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use App\Models\Cabecera;

class GenerarCabeceras extends Command
{
    protected $signature = 'seo:generar-cabeceras';
    protected $description = 'Genera registros en cabeceras para productos que no tienen uno';

    public function handle()
    {
        $productos = Producto::all();
        $creados = 0;
        $existente = 0;

        foreach ($productos as $producto) {
            $existe = Cabecera::where('ruta', $producto->ruta)->exists();
            if ($existe) {
                $existente++;
                continue;
            }

            Cabecera::create([
                'ruta'           => $producto->ruta,
                'titulo'         => $producto->titulo ?? 'Sin título',
                'descripcion'    => $producto->descripcion ?? $producto->titulo ?? 'Sin descripción',
                'palabras_claves' => $producto->titulo ?? '',
                'portada'        => $producto->portada ?: 'defaults/default-portada.jpg',
                'fecha'          => now(),
            ]);
            $creados++;
        }

        $this->info("Cabeceras existentes: {$existente}");
        $this->info("Cabeceras creadas: {$creados}");
        $this->info("Total productos: " . $productos->count());
    }
}
