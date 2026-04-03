<?php

$entrada = fopen("productos_scraper.csv", "r");
$salida = fopen("productos_final.csv", "w");

// usar ; como separador
$sep = ";";

// ENCABEZADOS EXACTOS (18 columnas)
$headers = [
"titulo","titular","descripcion","precio","portada","multimedia",
"tipo","estado","ruta","vistas","ventas","vistasGratis",
"ventasGratis","categoria_id","subcategoria_id","marca_id",
"proveedor_id","fecha"
];

fputcsv($salida, $headers, $sep);

// saltar cabecera del scraper
fgetcsv($entrada);

while(($data = fgetcsv($entrada,1000,",")) !== FALSE){

    if(count($data) < 3) continue;

    $nombre = trim($data[0]);
    $precio = trim($data[1]);
    $imagen = trim($data[2]);

    // 🔥 LIMPIEZA TOTAL (CLAVE)
    $nombre = preg_replace('/[\r\n";,]+/', ' ', $nombre);
    $descripcion = "Maquinaria industrial disponible para importacion Entrega 20-30 dias";
    $descripcion = preg_replace('/[\r\n";,]+/', ' ', $descripcion);

    $ruta = strtolower(str_replace(" ", "-", $nombre));

    $vistas = rand(100,900);
    $ventas = rand(1,30);

    $fila = [
        $nombre,$nombre,$descripcion,$precio,$imagen,$imagen,
        "fisico",1,$ruta,$vistas,$ventas,0,0,1,1,1,1,date("Y-m-d")
    ];

    // validar columnas (DEBUG)
    if(count($fila) != count($headers)){
        echo "❌ Error en fila<br>";
        continue;
    }

    fputcsv($salida, $fila, $sep);
}

fclose($entrada);
fclose($salida);

echo "✅ CSV PERFECTO generado";