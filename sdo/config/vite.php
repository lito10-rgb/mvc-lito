<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dev server
    |--------------------------------------------------------------------------
    */
    'dev_server' => [
        'url' => env('VITE_DEV_SERVER_URL', 'http://localhost:5173'),
        'enabled' => env('APP_ENV') === 'local',
    ],

    /*
    |--------------------------------------------------------------------------
    | Build path para producción
    |--------------------------------------------------------------------------
    | Esta es la parte importante.
    | Usamos base_path('../public_html') para que Laravel encuentre public_html
    | aunque el proyecto real esté dentro de mvc-lito.
    |--------------------------------------------------------------------------
    */

    'build_path' => base_path('../public_html/build'),

    /*
    |--------------------------------------------------------------------------
    | Manifest
    |--------------------------------------------------------------------------
    | Laravel encontrará manifest.json dentro del build_path.
    |--------------------------------------------------------------------------
    */

    'manifest' => base_path('../public_html/build/manifest.json'),

    /*
    |--------------------------------------------------------------------------
    | Entradas
    |--------------------------------------------------------------------------
    */
    'entrypoints' => [
        'resources/js/app.js',
        'resources/css/app.css',
    ],

];
