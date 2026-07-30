<?php
header('Content-Type: text/plain');
echo "=== DIAGNÓSTICO MVC-LITO ===\n\n";

echo "PHP Version: " . phpversion() . "\n";

echo "\n-- Directorios --\n";
$dirs = [
    '__DIR__' => __DIR__,
    'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
    'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'] ?? 'N/A',
];
foreach ($dirs as $k => $v) echo "$k = $v\n";

echo "\n-- vendor/autoload.php --\n";
$f = __DIR__ . '/../vendor/autoload.php';
if (file_exists($f)) {
    echo "EXISTS (" . filesize($f) . " bytes)\n";
} else {
    $f2 = __DIR__ . '/../../vendor/autoload.php';
    if (file_exists($f2)) {
        echo "EXISTS at parent: $f2 (" . filesize($f2) . " bytes)\n";
    } else {
        echo "NOT FOUND\n";
    }
}

echo "\n-- .env --\n";
for ($i = 0; $i < 3; $i++) {
    $base = $i === 0 ? __DIR__ : dirname($base ?? __DIR__);
    $envFile = $base . '/.env';
    if (file_exists($envFile)) {
        echo "FOUND at: $envFile\n";
        $content = file_get_contents($envFile);
        preg_match('/^APP_KEY=(.*)$/m', $content, $m);
        echo "APP_KEY = " . ($m[1] ?? 'NOT SET') . "\n";
        preg_match('/^DB_USERNAME=(.*)$/m', $content, $m);
        echo "DB_USERNAME = " . ($m[1] ?? 'NOT SET') . "\n";
        preg_match('/^DB_DATABASE=(.*)$/m', $content, $m);
        echo "DB_DATABASE = " . ($m[1] ?? 'NOT SET') . "\n";
        preg_match('/^APP_DEBUG=(.*)$/m', $content, $m);
        echo "APP_DEBUG = " . ($m[1] ?? 'NOT SET') . "\n";
        break;
    }
}

echo "\n-- bootstrap/cache/ --\n";
$cacheDir = __DIR__ . '/../bootstrap/cache';
if (is_dir($cacheDir)) {
    echo "DIR EXISTS\n";
    $files = glob($cacheDir . '/*.php');
    echo "PHP files: " . count($files) . "\n";
    foreach ($files as $f) echo "  " . basename($f) . "\n";
} else {
    echo "DIR NOT FOUND\n";
}

echo "\n-- storage/ --\n";
$storageDir = __DIR__ . '/../storage';
if (is_dir($storageDir)) {
    echo "DIR EXISTS\n";
    $frameworkDir = $storageDir . '/framework';
    if (is_dir($frameworkDir)) {
        echo "storage/framework: EXISTS\n";
        foreach (glob($frameworkDir . '/*', GLOB_ONLYDIR) as $d) {
            echo "  " . basename($d) . " - " . (is_writable($d) ? 'WRITABLE' : 'NOT WRITABLE') . "\n";
        }
    }
    $logsDir = $storageDir . '/logs';
    if (is_dir($logsDir)) {
        echo "storage/logs: EXISTS - " . (is_writable($logsDir) ? 'WRITABLE' : 'NOT WRITABLE') . "\n";
    }
} else {
    echo "DIR NOT FOUND\n";
}

echo "\n-- Try requiring vendor/autoload.php --\n";
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    try {
        require $autoload;
        echo "vendor/autoload.php: LOADED OK\n";
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
} else {
    echo "Not found at $autoload\n";
}

echo "\nDone.\n";