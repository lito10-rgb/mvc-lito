<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/cafe-peruano/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/cafe-peruano/vendor/autoload.php';

$app = require_once __DIR__.'/cafe-peruano/bootstrap/app.php';

$app->handleRequest(Request::capture());
