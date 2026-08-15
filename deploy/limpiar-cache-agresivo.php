<?php
/**
 * Script de limpieza de caché agresivo para cPanel
 * Colocar este archivo en public/ del servidor
 * Ejecutar via: https://cafe-peruano.com/limpiar-cache-agresivo.php
 */

// Deshabilitar límites de tiempo para scripts largos
set_time_limit(300);

// Directorios a limpiar
$directories = [
    __DIR__ . '/../storage/framework/cache',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../bootstrap/cache',
];

echo "🧹 LIMPIEZA AGRESIVA DE CACHÉ\n";
echo "================================\n\n";

function removeDirectoryContents($dir) {
    if (!is_dir($dir)) {
        echo "⚠️  Directorio no existe: $dir\n";
        return false;
    }

    $files = glob($dir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                echo "✅ Eliminado: $file\n";
            } else {
                echo "❌ Error al eliminar: $file\n";
            }
        } elseif (is_dir($file)) {
            removeDirectoryContents($file);
            rmdir($file);
        }
    }
    return true;
}

// Limpiar cada directorio
foreach ($directories as $dir) {
    echo "📦 Limpiando: $dir\n";
    removeDirectoryContents($dir);
    echo "\n";
}

// Intentar crear archivo .htaccess para deshabilitar caché de navegador para este script
$htaccessContent = "ExpiresActive Off\nExpiresByType None\n";
file_put_contents(__DIR__ . '/.htaccess', $htaccessContent);

echo "================================\n";
echo "✅ LIMPIEZA COMPLETADA\n";
echo "================================\n";
echo "🔍 PASOS ADICIONALES:\n";
echo "1. Refresca el navegador (Ctrl+F5)\n";
echo "2. Borra caché del navegador manualmente si persiste\n";
echo "3. Verifica los cambios en el sitio\n";
echo "\n";

// Redirigir al home después de 3 segundos
echo "<meta http-equiv='refresh' content='3;url=/'>";
echo "<p>Redirigiendo al home en 3 segundos...</p>";
?>