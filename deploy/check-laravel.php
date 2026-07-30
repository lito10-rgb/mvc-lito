<?php
echo "--- Checking vendor/autoload.php ---\n";
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    echo "vendor/autoload.php: EXISTS\n";
} else {
    echo "vendor/autoload.php: MISSING\n";
}

echo "\n--- Checking bootstrap/app.php ---\n";
if (file_exists(__DIR__.'/bootstrap/app.php')) {
    echo "bootstrap/app.php: EXISTS\n";
} else {
    echo "bootstrap/app.php: MISSING\n";
}

echo "\n--- Checking .env file ---\n";
if (file_exists(__DIR__.'/.env')) {
    echo ".env: EXISTS\n";
    $env = file_get_contents(__DIR__.'/.env');
    echo "Size: " . strlen($env) . " bytes\n";
    // Check APP_KEY without revealing it
    if (preg_match('/^APP_KEY=(.+)$/m', $env, $m)) {
        $key = $m[1];
        echo "APP_KEY: " . (empty($key) ? "EMPTY!" : "present (" . strlen($key) . " chars)") . "\n";
    } else {
        echo "APP_KEY: NOT FOUND\n";
    }
} else {
    echo ".env: MISSING\n";
}

echo "\n--- Checking storage/logs ---\n";
$logDir = __DIR__.'/storage/logs';
if (is_dir($logDir)) {
    echo "storage/logs: EXISTS\n";
    $files = glob($logDir.'/*.log');
    if ($files) {
        echo "Log files found:\n";
        foreach ($files as $f) {
            $size = filesize($f);
            echo "  " . basename($f) . " (" . $size . " bytes)\n";
            if ($size > 0 && $size < 100000) {
                echo "  --- Last 20 lines ---\n";
                $lines = file($f);
                $last = array_slice($lines, -20);
                foreach ($last as $l) {
                    echo "  " . $l;
                }
            }
        }
    } else {
        echo "No .log files found\n";
    }
} else {
    echo "storage/logs: MISSING\n";
}

echo "\n--- Checking storage/framework ---\n";
if (is_dir(__DIR__.'/storage/framework')) {
    echo "storage/framework: EXISTS\n";
} else {
    echo "storage/framework: MISSING\n";
}
