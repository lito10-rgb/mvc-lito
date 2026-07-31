<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    $base = dirname(__DIR__);
    require $base . '/vendor/autoload.php';
    $app = require_once $base . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $status = $kernel->handle(
        new Symfony\Component\Console\Input\ArrayInput(['command' => 'migrate', '--force' => true]),
        $output = new Symfony\Component\Console\Output\BufferedOutput()
    );
    echo "STATUS: $status\n";
    echo $output->fetch();
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
