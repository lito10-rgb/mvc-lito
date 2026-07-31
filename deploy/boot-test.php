<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', '1');
try {
    $base = dirname(__DIR__);
    require $base . '/vendor/autoload.php';
    $app = require_once $base . '/bootstrap/app.php';
    echo "Boot test SUCCESS!";
} catch (\Throwable $e) {
    echo "BOOT ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
