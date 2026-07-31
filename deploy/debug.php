<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "PHP Version: " . PHP_VERSION . "\n";
$base = dirname(__DIR__);
echo "Base path: $base\n";

echo ".env exists: " . (file_exists($base . '/.env') ? 'YES' : 'NO') . "\n";
if (file_exists($base . '/.env')) {
    $env = file_get_contents($base . '/.env');
    echo ".env size: " . strlen($env) . " bytes\n";
    if (preg_match('/^APP_KEY=(.+)$/m', $env, $m)) {
        echo "APP_KEY: " . (trim($m[1]) ? 'PRESENT (' . strlen(trim($m[1])) . ' chars)' : 'EMPTY') . "\n";
    } else {
        echo "APP_KEY: NOT FOUND\n";
    }
}

echo "vendor/autoload.php exists: " . (file_exists($base . '/vendor/autoload.php') ? 'YES' : 'NO') . "\n";
echo "bootstrap/app.php exists: " . (file_exists($base . '/bootstrap/app.php') ? 'YES' : 'NO') . "\n";

$logFile = $base . '/storage/logs/laravel.log';
echo "laravel.log exists: " . (file_exists($logFile) ? 'YES' : 'NO') . "\n";
if (file_exists($logFile)) {
    echo "--- LAST LOG LINES ---\n";
    $lines = file($logFile);
    $last = array_slice($lines, -30);
    foreach ($last as $l) {
        echo $l;
    }
}
