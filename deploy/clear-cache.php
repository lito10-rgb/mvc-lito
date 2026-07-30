<?php
$base = __DIR__;
for ($i = 0; $i < 3; $i++) {
    if (file_exists($base . '/artisan') || is_dir($base . '/bootstrap')) {
        break;
    }
    $base = dirname($base);
}
$dirs = [
    $base . '/bootstrap/cache',
    $base . '/storage/framework/cache/data',
    $base . '/storage/framework/sessions',
    $base . '/storage/framework/testing',
    $base . '/storage/framework/views',
    $base . '/storage/logs',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "CREATED: $dir\n";
    }
}
$files = glob($base . '/bootstrap/cache/*.php');
$deleted = 0;
foreach ($files as $f) {
    if (unlink($f)) {
        echo "DELETED: " . basename($f) . "\n";
        $deleted++;
    } else {
        echo "FAILED: " . basename($f) . "\n";
    }
}
echo "Deleted $deleted cache files\n";