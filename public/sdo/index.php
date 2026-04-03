<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Modo mantenimiento
if (file_exists($maintenance = __DIR__.'/../mvc-lito/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoload de Composer
require __DIR__.'/../mvc-lito/vendor/autoload.php';

// Bootstrapping de Laravel
$app = require_once __DIR__.'/../mvc-lito/bootstrap/app.php';

/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);

// Captura la petición y genera la respuesta
$request = Request::capture();
$response = $kernel->handle($request);

// Envía la respuesta al navegador
$response->send();

// Termina la petición (limpieza de middleware, eventos, etc.)
$kernel->terminate($request, $response);
