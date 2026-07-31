<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    $base = dirname(__DIR__);
    require $base . '/vendor/autoload.php';
    $app = require_once $base . '/bootstrap/app.php';
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Laravel DB Connection: OK\n";
    
    $status = $kernel->call('migrate', ['--force' => true]);
    echo "Migration exit code: $status\n";
    echo $kernel->output();
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
