<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
header('Content-Type: text/plain; charset=UTF-8');
ini_set('implicit_flush', true);
ob_implicit_flush(true);
@ob_end_flush();

register_shutdown_function(function () {
    $err = error_get_last();
    if ($err) {
        echo "\n--- SHUTDOWN LAST ERROR ---\n";
        foreach ($err as $k => $v) {
            echo $k . ': ' . $v . "\n";
        }
    }
});

$base = dirname(__DIR__);
echo "STEP A0: start, PHP " . PHP_VERSION . " sapi=" . php_sapi_name() . "\n";
echo "memory_limit=" . ini_get('memory_limit') . " max_execution_time=" . ini_get('max_execution_time') . "\n";
echo "STEP A1: base=$base\n";

echo "STEP A2: checks\n";
$checks = [
    $base . '/bootstrap/providers.php',
    $base . '/bootstrap/cache',
    $base . '/config/app.php',
    $base . '/config/database.php',
    $base . '/routes/web.php',
    $base . '/vendor/autoload.php',
    $base . '/vendor/composer/autoload_classmap.php',
    $base . '/vendor/composer/autoload_psr4.php',
    $base . '/vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
    $base . '/vendor/laravel/framework/src/Illuminate/Filesystem/FilesystemServiceProvider.php',
    $base . '/vendor/laravel/framework/src/Illuminate/Container/Container.php',
    $base . '/app/Providers/AppServiceProvider.php',
    $base . '/app/Helpers/negocio.php',
    $base . '/vendor/laravel/framework/composer.json',
];
foreach ($checks as $c) {
    echo (file_exists($c) ? 'OK ' : 'MISSING ') . $c;
    if (file_exists($c)) {
        echo ' (' . filesize($c) . ' bytes)';
    }
    echo "\n";
}

echo "STEP A3: bootstrap/cache contents\n";
$cacheDir = $base . '/bootstrap/cache';
if (is_dir($cacheDir)) {
    $files = scandir($cacheDir);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') {
            continue;
        }
        $p = $cacheDir . '/' . $f;
        echo (is_file($p) ? 'FILE ' : 'DIR  ') . $f . (is_file($p) ? ' (' . filesize($p) . ' bytes)' : '') . "\n";
    }
} else {
    echo "cache dir not present\n";
}

echo "STEP A4: providers.php content\n";
if (file_exists($base . '/bootstrap/providers.php')) {
    var_export(require $base . '/bootstrap/providers.php');
    echo "\n";
} else {
    echo "providers.php MISSING\n";
}

use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;

echo "STEP B1: require autoload\n";
require $base . '/vendor/autoload.php';
echo "STEP B2: require bootstrap/app.php\n";
$app = require_once $base . '/bootstrap/app.php';
echo "STEP B3: app created, version=" . $app->version() . "\n";

echo "STEP B4: resolve kernel\n";
try {
    $kernel = $app->make(HttpKernelContract::class);
    echo "STEP B5: kernel resolved OK\n";
} catch (\Throwable $e) {
    echo "STEP B5 FAIL: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    exit;
}

echo "STEP B6: kernel handle\n";
try {
    $response = $kernel->handle(Request::capture());
    echo "STEP B7: handle OK, status=" . $response->getStatusCode() . "\n";
    echo "Content:\n" . substr($response->getContent(), 0, 3000) . "\n";
} catch (\Throwable $e) {
    echo "STEP B7 FAIL: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "STEP C1: end\n";
