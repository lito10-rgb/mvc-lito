<?php
// clear_cache.php

echo "Limpiando caches de Laravel...\n";

// Limpiar rutas
echo shell_exec('php artisan route:clear');

// Limpiar config
echo shell_exec('php artisan config:clear');

// Limpiar cache general
echo shell_exec('php artisan cache:clear');

// Limpiar vistas compiladas
echo shell_exec('php artisan view:clear');

// Autoload
echo shell_exec('composer dump-autoload');

echo "Listo!\n";
