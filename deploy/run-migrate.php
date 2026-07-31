<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "Step 1: Starting...\n";
$base = dirname(__DIR__);
echo "Base: $base\n";

if (file_exists($base . '/vendor/autoload.php')) {
    require $base . '/vendor/autoload.php';
    echo "Step 2: Autoload loaded successfully.\n";
} else {
    echo "ERROR: vendor/autoload.php missing!\n";
    exit;
}

if (file_exists($base . '/bootstrap/app.php')) {
    $app = require_once $base . '/bootstrap/app.php';
    echo "Step 3: App booted successfully.\n";
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $output = new Symfony\Component\Console\Output\BufferedOutput();
    $kernel->handle(
        new Symfony\Component\Console\Input\ArrayInput(['command' => 'migrate', '--force' => true]),
        $output
    );
    echo "Step 4: Migrations executed!\n";
    echo $output->fetch();
} else {
    echo "ERROR: bootstrap/app.php missing!\n";
}
