<?php
echo "PHP OK\n";
echo "Version: " . PHP_VERSION . "\n";
echo "Extensions:\n";
foreach (get_loaded_extensions() as $ext) {
    echo "  $ext\n";
}
