<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cache:limpiar-agresivo', function () {
    $this->info('🧹 Limpiando caché agresivamente...');
    try {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        \Artisan::call('optimize:clear');
        $this->info('✅ Caché limpiado completamente');
    } catch (\Exception $e) {
        $this->error('❌ Error: ' . $e->getMessage());
    }
})->purpose('Limpiar todos los cachés agresivamente');
