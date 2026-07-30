<?php
$base = __DIR__;
for ($i = 0; $i < 3; $i++) {
    if (file_exists($base . '/artisan') || is_dir($base . '/bootstrap')) {
        break;
    }
    $base = dirname($base);
}
$cacheDir = $base . '/bootstrap/cache';
if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
    echo "CREATED: $cacheDir\n";
}
$files = glob($cacheDir . '/*.php');
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