<?php

$entrada = fopen("productos_scraper.csv", "r");
$salida = fopen("productos_final.csv", "w");

// encabezados
fputcsv($salida, [
"titulo",
"titular",
"descripcion",
"precio",
"portada",
"multimedia",
"tipo",
"estado",
"ruta",
"vistas",
"ventas",
"vistasGratis",
"ventasGratis",
"categoria_id",
"subcategoria_id",
"marca_id",
"proveedor_id",
"fecha"
]);

while(($data = fgetcsv($entrada,1000,",")) !== FALSE){

    // VALIDAR columnas
    if(count($data) < 3){
        continue; // saltar filas vacías o incorrectas
    }

    $nombre = $data[0];
    $precio = $data[1]; // ya en soles
    $imagen = $data[2];

    // limpiar precio (por si tiene comas)
    $precio = str_replace(",", "", $precio);

    // slug
    $ruta = strtolower(str_replace(" ","-",$nombre));

    // mejorar imagen
    $imagen = str_replace("_300x300.jpg",".jpg",$imagen);

    // datos generados
    $descripcion = "Maquinaria industrial disponible para importación. Entrega 20-30 días.";

    $vistas = rand(100,900);
    $ventas = rand(1,30);

    fputcsv($salida,[
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
    ]);
}

fclose($entrada);
fclose($salida);

echo "CSV generado correctamente";