<?php

if (! function_exists('limpiar_ruta_multimedia')) {
    /**
     * Normaliza una ruta de imagen eliminando escapes, entidades HTML y
     * caracteres de envoltura JSON, hasta que el valor se estabilice.
     */
    function limpiar_ruta_multimedia(string $valor): string
    {
        $previo = null;
        while ($valor !== $previo) {
            $previo = $valor;
            $valor = html_entity_decode($valor, ENT_QUOTES, 'UTF-8');
            $valor = stripslashes($valor);
            $valor = trim($valor, "[]\"' \t\n\r");
        }

        return trim($valor);
    }
}

if (! function_exists('multimedia_producto')) {
    /**
     * Extrae las rutas limpias de imágenes del campo multimedia de un producto,
     * tolerando cualquier nivel de escape o codificación del JSON almacenado.
     *
     * @return string[] Rutas relativas dentro de storage (ej. productos/multimedia/x/foto.jpg)
     */
    function multimedia_producto($producto): array
    {
        $raw = $producto->multimedia ?? '';
        if (! is_string($raw) || strlen($raw) === 0) {
            return [];
        }

        $imagenes = [];
        $valor = $raw;

        for ($nivel = 0; $nivel < 6; $nivel++) {
            $valor = html_entity_decode($valor, ENT_QUOTES, 'UTF-8');
            $decodificado = json_decode($valor, true);

            if (is_array($decodificado)) {
                foreach ($decodificado as $item) {
                    $imagenes[] = is_array($item) ? ($item['foto'] ?? '') : $item;
                }
                break;
            }

            if (is_string($decodificado)) {
                $valor = $decodificado;

                continue;
            }

            $limpio = stripslashes($valor);
            if ($limpio !== $valor) {
                $valor = $limpio;

                continue;
            }
            break;
        }

        if ($imagenes === []) {
            $imagenes = explode(',', $valor);
        }

        $limpias = [];
        foreach ($imagenes as $img) {
            if (! is_string($img)) {
                continue;
            }
            $img = limpiar_ruta_multimedia($img);
            if ($img !== '' && ! str_contains($img, '&quot;') && ! str_contains($img, '\\') && ! str_contains($img, '[')) {
                $limpias[] = $img;
            }
        }

        return array_values(array_unique($limpias));
    }
}

if (! function_exists('multimedia_primera_imagen')) {
    /**
     * Devuelve la primera imagen limpia del campo multimedia, o null si no hay.
     */
    function multimedia_primera_imagen($producto): ?string
    {
        $imagenes = multimedia_producto($producto);

        return $imagenes[0] ?? null;
    }
}
