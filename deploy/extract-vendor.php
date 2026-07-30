<?php
// This script lives at public_html/deploy/extract-vendor.php
// project.zip is at public_html/project.zip
// We need to extract into public_html/
$base = dirname(__DIR__);

// Extract full project zip
$zipFile = $base . '/project.zip';
if (file_exists($zipFile)) {
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === true) {
        $extracted = $zip->extractTo($base);
        $zip->close();
        if ($extracted) {
            echo "Extracted project.zip successfully\n";
            unlink($zipFile);
        } else {
            echo "ERROR: Failed to extract project.zip\n";
        }
    } else {
        echo "ERROR: Failed to open project.zip\n";
    }
} else {
    echo "No project.zip found\n";
}

// Also try vendor.zip for backward compatibility
$vendorZip = $base . '/vendor.zip';
if (file_exists($vendorZip)) {
    $zip = new ZipArchive;
    if ($zip->open($vendorZip) === true) {
        $target = $base . '/vendor';
        if (!is_dir($target)) mkdir($target, 0755, true);
        $extracted = $zip->extractTo($target);
        $zip->close();
        if ($extracted) {
            echo "Extracted vendor.zip successfully\n";
            unlink($vendorZip);
        }
    }
}

// Create required Laravel directories
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
