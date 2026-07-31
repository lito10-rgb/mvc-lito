<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$base = __DIR__;
for ($i = 0; $i < 3; $i++) {
    if (file_exists($base . '/artisan') || is_dir($base . '/bootstrap')) {
        break;
    }
    $base = dirname($base);
}

echo "Base: $base\n";
$logFile = $base . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "--- LAST LOG FILE ---\n";
    echo file_get_contents($logFile);
} else {
    echo "Log file not found at: $logFile\n";
}
