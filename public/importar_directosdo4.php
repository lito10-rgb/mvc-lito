<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "", "mqyeq");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$archivo = fopen("productos_scraper10.csv", "r");

if (!$archivo) {
    die("No se pudo abrir el archivo");
}

$contador = 0;
if (!is_dir("public/uploads/productos")) {
    mkdir("public/uploads/productos", 0777, true);
}
while (($linea = fgets($archivo)) !== false) {

    // 🔍 detectar separador por fila
    if (strpos($linea, "\t") !== false) {
        $data = explode("\t", $linea);
    } elseif (strpos($linea, ";") !== false) {
        $data = explode(";", $linea);
    } else {
        $data = explode(",", $linea);
    }

    // limpiar espacios
    $data = array_map('trim', $data);

    // validar columnas
    if (count($data) < 7) {
        echo "⚠️ Fila incompleta:<br>";
        print_r($data);
        echo "<br><br>";
        continue;
    }

    $nombre = $data[0];
    $precio = $data[1];
    $imagen_url = $data[2];

    $categoria_id = intval($data[3]);
    $subcategoria_id = intval($data[4]);
    $marca_id = intval($data[5]);
    $proveedor_id = intval($data[6]);

    echo "➡ Procesando: $nombre <br>";
    echo "Cat:$categoria_id Sub:$subcategoria_id Marca:$marca_id Prov:$proveedor_id <br>";

    // 🔧 limpiar precio
    $precio = str_replace(",", ".", $precio);
    $precio = floatval($precio);
    $precio = $precio * 3.9 * 1.6;

    echo "Precio final: $precio <br>";

    // 🔤 generar slug
    $nombre_limpio = strtolower($nombre);
    $nombre_limpio = preg_replace('/[^a-z0-9]+/', '-', $nombre_limpio);
    $nombre_limpio = trim($nombre_limpio, '-');

    // 📥 descargar imagen
    $ruta_bd = "";

    if (!empty($imagen_url)) {
        $contenido = @file_get_contents($imagen_url);

        if ($contenido !== false) {
            $ruta_local = "public/uploads/productos/" . $nombre_limpio . ".jpg";
            file_put_contents($ruta_local, $contenido);
            $ruta_bd = "uploads/productos/" . $nombre_limpio . ".jpg";
        } else {
            echo "⚠️ No se pudo descargar imagen<br>";
        }
    }

    $descripcion = "Maquinaria industrial disponible para importación";
    $ruta = $nombre_limpio;

    $vistas = rand(100, 900);
    $ventas = rand(1, 30);

    // 🔍 VALIDAR FK

    $check = $conn->query("SELECT id FROM categorias WHERE id = $categoria_id");
    if ($check->num_rows == 0) {
        echo "❌ Categoria NO existe: $categoria_id <br><br>";
        continue;
    }

    $check = $conn->query("SELECT id FROM subcategorias WHERE id = $subcategoria_id");
    if ($check->num_rows == 0) {
        echo "❌ Subcategoria NO existe: $subcategoria_id <br><br>";
        continue;
    }

    $check = $conn->query("SELECT id FROM marcas WHERE id = $marca_id");
    if ($check->num_rows == 0) {
        echo "❌ Marca NO existe: $marca_id <br><br>";
        continue;
    }

    $check = $conn->query("SELECT id FROM proveedores WHERE id = $proveedor_id");
    if ($check->num_rows == 0) {
        echo "❌ Proveedor NO existe: $proveedor_id <br><br>";
        continue;
    }

    // 🚀 INSERT
    $sql = "INSERT INTO productos (
        titulo, titular, descripcion, precio, portada, multimedia,
        tipo, estado, ruta, vistas, ventas, vistasGratis,
        ventasGratis, categoria_id, subcategoria_id,
        marca_id, proveedor_id, fecha
    ) VALUES (
        '$nombre',
        '$nombre',
        '$descripcion',
        $precio,
        '$ruta_bd',
        '$ruta_bd',
        'fisico',
        1,
        '$ruta',
        $vistas,
        $ventas,
        0,
        0,
        $categoria_id,
        $subcategoria_id,
        $marca_id,
        $proveedor_id,
        NOW()
    )";

    echo "➡ Intentando INSERT...<br>";

    if ($conn->query($sql)) {
        echo "✅ Insertado correctamente<br><br>";
        $contador++;
    } else {
        echo "❌ Error SQL: " . $conn->error . "<br><br>";
    }
}

fclose($archivo);
$conn->close();

echo "<hr>";
echo "✅ TOTAL INSERTADOS: " . $contador;

?>