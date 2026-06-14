<?php

$conn = new mysqli("localhost", "root", "", "mqyeq");

$archivo = fopen("productos_scraper.csv", "r");
fgetcsv($archivo);

$contador = 0;

while(($data = fgetcsv($archivo,1000,",")) !== FALSE){

    if(count($data) < 3) continue;

    $nombre = trim($data[0]);
    $precio = trim($data[1]);
    $imagen_url = trim($data[2]);
    ////////////
    $categoria_id = intval($data[3]);
    $subcategoria_id = intval($data[4]);
    $marca_id = intval($data[5]);
    $proveedor_id = intval($data[6]);
    // limpiar nombre
    $nombre_limpio = strtolower(str_replace(" ", "-", $nombre));
    $nombre_limpio = preg_replace('/[^a-z0-9\-]/', '', $nombre_limpio);

    // 📥 DESCARGAR IMAGEN
    $contenido = @file_get_contents($imagen_url);

    if($contenido !== false){

        $ruta_local = "public/uploads/productos/" . $nombre_limpio . ".jpg";

        file_put_contents($ruta_local, $contenido);

        // ruta para BD
        $ruta_bd = "uploads/productos/" . $nombre_limpio . ".jpg";

    } else {
        $ruta_bd = ""; // fallback
    }

    $descripcion = "Maquinaria industrial disponible para importacion";
    $ruta = $nombre_limpio;

    $vistas = rand(100,900);
    $ventas = rand(1,30);
    
    $sql = "INSERT INTO productos (
        titulo, titular, descripcion, precio, portada, multimedia,
        tipo, estado, ruta, vistas, ventas, vistasGratis,
        ventasGratis, categoria_id, subcategoria_id,
        marca_id, proveedor_id, fecha
    ) VALUES (
        '$nombre',
        '$nombre',
        '$descripcion',
        '$precio',
        '$ruta_bd',
        '$ruta_bd',
        'fisico',
        1,
        '$ruta',
        $vistas,
        $ventas,
        0,
        0,
        -- $categoria_id,
        -- $subcategoria_id,
        -- $marca_id,
        -- $proveedor_id,
        $categoria_id = intval($data[3]);
        $subcategoria_id = intval($data[4]);
        $marca_id = intval($data[5]);
        $proveedor_id = intval($data[6]);
        NOW()
    )";

    if($conn->query($sql)){
        $contador++;
    }
}

fclose($archivo);
$conn->close();

echo "✅ Productos insertados: " . $contador;