<?php
header('Content-Type: text/plain');
$base = __DIR__;
for ($i = 0; $i < 3; $i++) {
    if (file_exists($base . '/artisan') || is_dir($base . '/bootstrap')) {
        break;
    }
    $base = dirname($base);
}
$logFile = $base . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "--- LAST LOG FILE ---\n";
    $lines = file($logFile);
    $last = array_slice($lines, -60);
    foreach ($last as $l) {
        echo $l;
    }
} else {
    echo "No laravel.log found at $logFile\n";
    $logsDir = $base . '/storage/logs';
    if (is_dir($logsDir)) {
        echo "Files in storage/logs:\n";
        print_r(scandir($logsDir));
    } else {
        echo "storage/logs directory does not exist.\n";
    }
}
