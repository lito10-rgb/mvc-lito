<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::send('emails.cotizacion', [
        'contenido' => nl2br(e('Prueba')),
        'cotizacion' => null,
    ], function ($m) {
        $m->to('informes@equiposymaquinas.com')->subject('Test');
    });
    echo 'ok';
} catch (Exception $e) {
    echo $e->getMessage();
}
