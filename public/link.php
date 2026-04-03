<?php

$target = __DIR__ . '/mvc-lito/storage/app/public';
$link = __DIR__ . '/storage';

if (symlink($target, $link)) {
    echo "LINK CREADO";
} else {
    echo "NO SE PUDO CREAR LINK";
}
