<?php
echo "<pre>";

// RUTA ABSOLUTA REAL (según tu hosting)
$basePath = '/home1/equipymaq/mvc-lito';

if (!file_exists($basePath . '/artisan')) {
    die("❌ No se encontró artisan en: $basePath");
}

chdir($basePath);

$commands = [
    'php artisan config:clear',
    'php artisan cache:clear',
    'php artisan view:clear',
    'php artisan optimize:clear',
];

foreach ($commands as $cmd) {
    echo "▶ Ejecutando: $cmd\n";
    system($cmd);
    echo "\n----------------------\n";
}

echo "✅ Cache limpiado correctamente\n";
echo "</pre>";
