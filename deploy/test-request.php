<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', '1');

use Illuminate\Http\Request;

try {
    $base = dirname(__DIR__);
    require $base . '/vendor/autoload.php';
    $app = require_once $base . '/bootstrap/app.php';

    $response = $app->handleRequest(Request::capture());
    echo "HTTP STATUS: " . $response->getStatusCode() . "\n";
    echo "CONTENT:\n" . substr($response->getContent(), 0, 1000);
} catch (\Throwable $e) {
    echo "EXCEPTION CATCH:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
}
