<?php
use Illuminate\Contracts\Console\Kernel;

define('LARAVEL_START', microtime(true));

// Determine base directory
$base = __DIR__;
for ($i = 0; $i < 3; $i++) {
    if (file_exists($base . '/artisan') || is_dir($base . '/bootstrap')) {
        break;
    }
    $base = dirname($base);
}

require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

try {
    $status = $kernel->handle(
        $input = new Symfony\Component\Console\Input\ArrayInput(['command' => 'migrate', '--force' => true]),
        $output = new Symfony\Component\Console\Output\BufferedOutput
    );
    
    echo "<h1>Migration Result</h1>";
    echo "<pre>" . htmlspecialchars($output->fetch()) . "</pre>";
} catch (\Throwable $e) {
    echo "<h1>Migration Error</h1>";
    echo "<pre>" . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . "</pre>";
}
