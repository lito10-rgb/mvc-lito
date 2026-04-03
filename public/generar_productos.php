<?php

$entrada = fopen("productos_scraper.csv", "r");
$salida = fopen("productos_final.csv", "w");

// separador seguro
$sep = ";";

// encabezados EXACTOS (18 columnas)
$headers = [
"titulo","titular","descripcion","precio","portada","multimedia",
"tipo","estado","ruta","vistas","ventas","vistasGratis",
"ventasGratis","categoria_id","subcategoria_id","marca_id",
"proveedor_id","fecha"
];

fputcsv($salida, $headers, $sep);

// saltar cabecera
fgetcsv($entrada);

while(($data = fgetcsv($entrada,1000,",")) !== FALSE){

    if(count($data) < 3) continue;

    $nombre = trim($data[0]);
    $precio = trim($data[1]);
    $imagen = trim($data[2]);

    // LIMPIEZA TOTAL
    $nombre = preg_replace('/[^A-Za-z0-9 áéíóúÁÉÍÓÚñÑ-]/u', ' ', $nombre);
    $descripcion = "Maquinaria industrial disponible para importacion Entrega 20-30 dias";

    // slug
    $ruta = strtolower(str_replace(" ", "-", $nombre));

    // valores
    $vistas = rand(100,900);
    $ventas = rand(1,30);

    // fila EXACTA (18 columnas)
    $fila = [
        $nombre,
        $nombre,
        $descripcion,
        $precio,
        $imagen,
        $imagen,
        "fisico",
        1,
        $ruta,
        $vistas,
        $ventas,
        0,
        0,
        1,
        1,
        1,
        1,
        date("Y-m-d")
    ];

    // VALIDACIÓN CLAVE
    if(count($fila) !== 18){
        echo "❌ Error columnas<br>";
        continue;
    }

    fputcsv($salida, $fila, $sep);
}

fclose($entrada);
fclose($salida);

echo "✅ CSV perfecto generado lito";