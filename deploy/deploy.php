<?php
$secret = getenv('DEPLOY_SECRET') ?: 'change-me-in-production';
if (($_POST['secret'] ?? '') !== $secret) {
    http_response_code(403);
    echo "ERROR: Invalid secret\n";
    exit;
}

$zipFile = __DIR__ . '/deploy.zip';
$size = 0;

// Handle file upload
if (!empty($_FILES['file']['tmp_name'])) {
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $zipFile)) {
        http_response_code(500);
        echo "ERROR: Failed to save upload\n";
        exit;
    }
    $size = filesize($zipFile);
    echo "Uploaded: " . round($size / 1024 / 1024, 1) . " MB\n";
} elseif (!empty($_SERVER['HTTP_X_FILE_PATH'])) {
    // Streamed upload via PUT
    $in = fopen('php://input', 'rb');
    $out = fopen($zipFile, 'wb');
    if ($in && $out) {
        while (!feof($in)) {
            fwrite($out, fread($in, 8192));
        }
        fclose($in);
        fclose($out);
    }
    $size = filesize($zipFile);
    echo "Streamed: " . round($size / 1024 / 1024, 1) . " MB\n";
} else {
    http_response_code(400);
    echo "ERROR: No file uploaded\n";
    exit;
}

// Extract zip
$zip = new ZipArchive;
if ($zip->open($zipFile) !== true) {
    http_response_code(500);
    echo "ERROR: Failed to open zip\n";
    exit;
}

$base = __DIR__;
$extracted = $zip->extractTo($base);
$zip->close();

if (!$extracted) {
    http_response_code(500);
    echo "ERROR: Extraction failed\n";
    unlink($zipFile);
    exit;
}

unlink($zipFile);
echo "Extracted successfully\n";

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
