<?php

// abrir archivos
$entrada = fopen("productos_scraper.csv", "r");
$salida = fopen("productos_final.csv", "w");

// ENCABEZADOS (deben coincidir con tu tabla)
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
], ",", '"');

// ⚠️ SALTAR PRIMERA FILA SI ES CABECERA
fgetcsv($entrada);

while(($data = fgetcsv($entrada,1000,",")) !== FALSE){

    // validar columnas mínimas
    if(count($data) < 3){
        continue;
    }

    $nombre = trim($data[0]);
    $precio = trim($data[1]); // YA EN SOLES
    $imagen = trim($data[2]);

    // limpiar precio
    $precio = str_replace(",", "", $precio);

    // limpiar textos (MUY IMPORTANTE)
    $nombre = str_replace([",", "\"", "\n"], " ", $nombre);

    // descripción limpia
    $descripcion = "Maquinaria industrial disponible para importacion. Entrega 20-30 dias.";
    $descripcion = str_replace([",", "\"", "\n"], " ", $descripcion);

    // slug (ruta)
    $ruta = strtolower($nombre);
    $ruta = str_replace(" ", "-", $ruta);

    // arreglar imagen (quitar tamaño pequeño)
    $imagen = str_replace("_300x300.jpg", ".jpg", $imagen);

    // valores ficticios
    $vistas = rand(100,900);
    $ventas = rand(1,30);

    // guardar fila (con comillas correctas)
    fputcsv($salida, [
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
    ], ",", '"');

}

fclose($entrada);
fclose($salida);

echo "✅ CSV generado correctamente";

?>