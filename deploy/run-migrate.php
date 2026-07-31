<?php
use Illuminate\Contracts\Console\Kernel;

define('LARAVEL_START', microtime(true));

$base = dirname(__DIR__);
require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

try {
    $output = new Symfony\Component\Console\Output\BufferedOutput();
    $status = $kernel->handle(
        new Symfony\Component\Console\Input\ArrayInput(['command' => 'migrate', '--force' => true]),
        $output
    );
    echo "<h1>Migration Successful!</h1>";
    echo "<pre>" . htmlspecialchars($output->fetch()) . "</pre>";
} catch (\Throwable $e) {
    echo "<h1>Migration Error</h1>";
    echo "<pre>" . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . "</pre>";
}
