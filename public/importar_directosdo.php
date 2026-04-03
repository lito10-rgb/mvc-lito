<?php

// 🔌 CONEXIÓN A TU BD (AJUSTA EL NOMBRE)
$conn = new mysqli("localhost", "root", "", "mqyeq");

if ($conn->connect_error) {
    die("❌ Error conexión: " . $conn->connect_error);
}

// 📂 ABRIR CSV DEL SCRAPER
$archivo = fopen("productos_scraper.csv", "r");

// ⛔ SALTAR CABECERA
fgetcsv($archivo);

$contador = 0;

while(($data = fgetcsv($archivo,1000,",")) !== FALSE){

    // validar columnas
    if(count($data) < 3) continue;

    // 🔥 DATOS BASE
    $nombre = trim($data[0]);
    $precio = trim($data[1]);
    $imagen = trim($data[2]);

    // 🧹 LIMPIEZA
    $nombre = $conn->real_escape_string($nombre);
    $precio = str_replace(",", "", $precio);
    $imagen = $conn->real_escape_string($imagen);

    // 📄 DESCRIPCIÓN
    $descripcion = "Maquinaria industrial disponible para importacion Entrega 20-30 dias";

    // 🔗 SLUG
    $ruta = strtolower(str_replace(" ", "-", $nombre));

    // 📊 DATOS FICTICIOS
    $vistas = rand(100,900);
    $ventas = rand(1,30);

    // 💾 INSERT
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
        '$imagen',
        '$imagen',
        'fisico',
        1,
        '$ruta',
        $vistas,
        $ventas,
        0,
        0,
        1,
        1,
        1,
        1,
        NOW()
    )";

    if($conn->query($sql)){
        $contador++;
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }
}

fclose($archivo);
$conn->close();

echo "✅ Productos insertados: " . $contador;

?>