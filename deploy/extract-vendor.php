<?php
$base = __DIR__;
$zipFile = $base . '/vendor.zip';
if (!file_exists($zipFile)) {
    echo "vendor.zip not found at $zipFile\n";
    exit(1);
}
$zip = new ZipArchive;
if ($zip->open($zipFile) !== true) {
    echo "Failed to open vendor.zip\n";
    exit(1);
}
$target = $base . '/vendor';
if (!is_dir($target)) mkdir($target, 0755, true);
$extracted = $zip->extractTo($target);
$zip->close();
if ($extracted) {
    echo "Extracted vendor/ successfully\n";
    unlink($zipFile);
    echo "Deleted vendor.zip\n";
} else {
    echo "Extraction failed\n";
    exit(1);
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