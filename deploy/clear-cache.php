<?php
$base = __DIR__;
// Go up from public/ if needed
for ($i = 0; $i < 3; $i++) {
    if (file_exists($base . '/bootstrap/cache/config.php') || file_exists($base . '/artisan')) {
        break;
    }
    $base = dirname($base);
}
$cacheDir = $base . '/bootstrap/cache';
if (!is_dir($cacheDir)) {
    echo "CACHE DIR NOT FOUND: $cacheDir\n";
    exit(1);
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
