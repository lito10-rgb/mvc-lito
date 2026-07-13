<?php
$opts = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]];
$ctx = stream_context_create($opts);

// Download domphar - a standalone PHAR of dompdf if available, or download individual packages
$packages = [
    'dompdf/dompdf' => [
        'url' => 'https://github.com/dompdf/dompdf/archive/refs/tags/v2.0.4.zip',
        'path' => 'vendor/dompdf',
    ]
];

foreach ($packages as $name => $info) {
    echo "Downloading $name...\n";
    $zip = file_get_contents($info['url'], false, $ctx);
    if (!$zip) { echo "FAILED to download $name\n"; continue; }
    file_put_contents(__DIR__ . '/tmp.zip', $zip);
    echo "  saved zip (" . strlen($zip) . " bytes)\n";
    
    $zipObj = new ZipArchive();
    if ($zipObj->open(__DIR__ . '/tmp.zip') === TRUE) {
        $zipObj->extractTo(__DIR__ . '/vendor_temp/');
        $zipObj->close();
        echo "  extracted\n";
    }
}
