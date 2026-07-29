<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use App\Models\Cabecera;

class GenerarCabeceras extends Command
{
    protected $signature = 'seo:generar-cabeceras';
    protected $description = 'Genera registros en cabeceras para productos que no tienen SEO';

    public function handle()
    {
        $productos = Producto::whereDoesntHave('cabecera')->get();
        $count = 0;

        foreach ($productos as $producto) {
            $palabras = $this->extraerPalabrasClave($producto->titulo);

            Cabecera::create([
                'ruta' => $producto->ruta,
                'titulo' => $producto->titulo,
                'descripcion' => $producto->titulo . ' — ' . ($producto->titular ?? $producto->titulo),
                'palabras_claves' => implode(', ', $palabras),
                'portada' => $producto->portada,
                'fecha' => now(),
            ]);
            $count++;
        }

        $this->info("Se generaron {$count} cabeceras para productos sin SEO.");
    }

    private function extraerPalabrasClave($titulo)
    {
        $palabras = explode(' ', $titulo);
        $palabras = array_filter($palabras, function ($p) {
            return mb_strlen($p) > 2;
        });
        $palabras = array_map(function ($p) {
            return trim(strtolower($p), ",.!¡¿?;:-");
        }, $palabras);
        $palabras = array_unique($palabras);
        $palabras = array_slice($palabras, 0, 8);

        if (empty($palabras)) {
            $palabras = [$titulo];
        }

        return $palabras;
    }
}
