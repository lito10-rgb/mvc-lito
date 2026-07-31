<?php
$base = __DIR__;
for ($i = 0; $i < 3; $i++) {
    if (file_exists($base . '/artisan') || is_dir($base . '/bootstrap')) {
        break;
    }
    $base = dirname($base);
}
header('Content-Type: text/plain');
echo "Base: $base\n";
echo "Artisan: " . (file_exists($base . '/artisan') ? 'YES' : 'NO') . "\n";
echo "Vendor: " . (file_exists($base . '/vendor/autoload.php') ? 'YES' : 'NO') . "\n";
echo "Env: " . (file_exists($base . '/.env') ? 'YES' : 'NO') . "\n";
