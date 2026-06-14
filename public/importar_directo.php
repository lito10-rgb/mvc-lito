<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli("localhost", "root", "", "mqyeq");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$archivo = fopen("productos_scraper.csv", "r");

if (!$archivo) {
    die("No se pudo abrir el archivo");
}

// 📁 carpeta imágenes
$carpeta = "public/uploads/productos/";
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

// 🔍 función obtener ID válido
function obtenerId($conn, $tabla){
    $res = $conn->query("SELECT id FROM $tabla LIMIT 1");
    if($row = $res->fetch_assoc()){
        return $row['id'];
    }
    return 1;
}

// obtener IDs reales
$categoria_id = obtenerId($conn, "categorias");
$subcategoria_id = obtenerId($conn, "subcategorias");
$marca_id = obtenerId($conn, "marcas");
$proveedor_id = obtenerId($conn, "proveedores");

// ⛔ saltar encabezado
fgets($archivo);

$contador = 0;

while (($linea = fgets($archivo)) !== false) {

    // 🔍 detectar separador
    if (strpos($linea, "\t") !== false) {
        $data = explode("\t", $linea);
    } elseif (strpos($linea, ";") !== false) {
        $data = explode(";", $linea);
    } else {
        $data = explode(",", $linea);
    }

    $data = array_map('trim', $data);

    if (count($data) < 3) {
        continue;
    }

    $nombre = $data[0];
    $precio = $data[1];
    $imagen_url = $data[2];

    // 🧹 limpiar BOM
    $nombre = str_replace("\xEF\xBB\xBF", '', $nombre);

    // 🔧 corregir encoding
    $nombre = mb_convert_encoding($nombre, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');

    // 🔧 limpiar precio
    $precio = str_replace(",", ".", $precio);
    $precio = floatval($precio);
    $precio = round($precio * 3.9 * 1.6, 2);

    // 🔤 slug limpio sin iconv
    $slug = strtolower($nombre);

    $reemplazos = [
        'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u',
        'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
        'ä'=>'a','ë'=>'e','ï'=>'i','ö'=>'o','ü'=>'u',
        'ñ'=>'n'
    ];

    $slug = strtr($slug, $reemplazos);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');

    // 📥 imagen (rápido: usar URL directa)
    $ruta_bd = $imagen_url;

    // 📊 datos base
    $descripcion = "Maquinaria industrial disponible para importación";
    $ruta = $slug;

    $vistas = rand(100, 900);
    $ventas = rand(1, 30);

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

    if ($conn->query($sql)) {
        $contador++;
    }
}

fclose($archivo);
$conn->close();

echo "✅ TOTAL INSERTADOS: " . $contador;

?>