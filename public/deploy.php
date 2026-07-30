<?php
// HTTP-based deploy endpoint for cafe-peruano.com
// Used when FTP is unavailable
$secret = 'cafe-peruano-2025-deploy';
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$token = $_POST['secret'] ?? $_GET['secret'] ?? '';

if ($token !== $secret) {
    http_response_code(403);
    die("ERROR: Invalid secret\n");
}

$base = __DIR__;

switch ($action) {
    case 'env':
        $envContent = $_POST['env'] ?? '';
        if (empty($envContent)) {
            http_response_code(400);
            die("ERROR: No env content\n");
        }
        file_put_contents($base . '/.env', $envContent);
        echo "OK: .env written (" . strlen($envContent) . " bytes)\n";
        break;

    case 'upload':
        if (empty($_FILES['file']['tmp_name'])) {
            http_response_code(400);
            die("ERROR: No file uploaded\n");
        }
        $dest = $base . '/' . ltrim($_POST['path'] ?? '', '/');
        $dir = dirname($dest);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
            echo "OK: " . basename($dest) . " uploaded\n";
        } else {
            http_response_code(500);
            echo "ERROR: Failed to save file\n";
        }
        break;

    case 'clear-cache':
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
        foreach (glob($base . '/bootstrap/cache/*.php') as $f) { unlink($f); echo "DELETED: $f\n"; }
        echo "Cache cleared\n";
        break;

    default:
        http_response_code(400);
        echo "ERROR: Unknown action. Use: env, upload, clear-cache\n";
}
