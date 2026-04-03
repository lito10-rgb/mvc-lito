<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Modo mantenimiento
if (file_exists($maintenance = __DIR__.'/../mvc-lito/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoload
require __DIR__.'/../mvc-lito/vendor/autoload.php';

// Bootstrap
$app = require_once __DIR__.'/../mvc-lito/bootstrap/app.php';

/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);

$request = Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
