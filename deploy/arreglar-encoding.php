<?php
/**
 * Script para arreglar encoding UTF-8 del servidor
 * Colocar este archivo en public/ del servidor
 * Ejecutar via: https://cafe-peruano.com/arreglar-encoding.php
 */

// Establecer encoding UTF-8
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
ini_set('default_charset', 'UTF-8');

// Modificar el archivo .env para asegurar encoding
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Verificar si APP_CHARSET existe, si no, agregarlo
    if (strpos($envContent, 'APP_CHARSET') === false) {
        $envContent .= "\nAPP_CHARSET=utf-8\n";
        file_put_contents($envFile, $envContent);
        echo "✅ Agregado APP_CHARSET=utf-8 al .env\n";
    } else {
        echo "ℹ️  APP_CHARSET ya existe en .env\n";
    }
    
    // Verificar si BROADCAST_CHANNEL está configurado
    if (strpos($envContent, 'BROADCAST_CHANNEL') === false) {
        $envContent = str_replace('APP_LOCALE', 'APP_LOCALE', $envContent);
        file_put_contents($envFile, $envContent);
        echo "✅ Configurado BROADCAST_CHANNEL\n";
    }
}

// Crear archivo .htaccess para forzar UTF-8
$htaccessContent = "
AddDefaultCharset UTF-8
<IfModule mod_php.c>
    php_value default_charset UTF-8
    php_value output_encoding UTF-8
    php_value input_encoding UTF-8
</IfModule>
AddCharset UTF-8 .php .html .htm .js .css
";

file_put_contents(__DIR__ . '/.htaccess', $htaccessContent);
echo "✅ Configurado .htaccess para UTF-8\n";

echo "\n================================\n";
echo "✅ CONFIGURACIÓN DE ENCODING COMPLETADA\n";
echo "================================\n";
echo "🔍 PRÓXIMOS PASOS:\n";
echo "1. Refresca el navegador (Ctrl+F5)\n";
echo "2. Borra caché del navegador manualmente\n";
echo "3. Verifica si los problemas de encoding se resolvieron\n";
echo "\n";

// Redirigir al home después de 3 segundos
echo "<meta http-equiv='refresh' content='3;url=/'>";
echo "<p>Redirigiendo al home en 3 segundos...</p>";
?>