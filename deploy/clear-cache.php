<?php
header('Content-Type: text/plain');
$base = __DIR__;
for ($i = 0; $i < 3; $i++) {
    if (file_exists($base . '/artisan') || is_dir($base . '/bootstrap')) {
        break;
    }
    $base = dirname($base);
}

echo "Base path: $base\n";

$cacheDirs = [
    $base . '/bootstrap/cache',
    $base . '/storage/framework/cache',
    $base . '/storage/framework/cache/data',
    $base . '/storage/framework/sessions',
    $base . '/storage/framework/testing',
    $base . '/storage/framework/views',
    $base . '/storage/logs',
];

foreach ($cacheDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
        echo "CREATED: $dir\n";
    }
    @chmod($dir, 0755);
}

// Delete all files in bootstrap/cache/
$files = array_merge(
    glob($base . '/bootstrap/cache/*'),
    glob($base . '/storage/framework/views/*'),
    glob($base . '/storage/framework/cache/data/*')
);

$deleted = 0;
if ($files) {
    foreach ($files as $f) {
        if (is_file($f)) {
            if (@unlink($f)) {
                echo "DELETED: " . basename($f) . "\n";
                $deleted++;
            }
        }
    }
}
echo "Deleted $deleted cached files.\n";