<?php

return [
    'site_name' => env('THEME_SITE_NAME', 'Equipos y Máquinas'),
    'site_slogan' => env('THEME_SITE_SLOGAN', 'Soluciones industriales para tu negocio. Fabricantes de maquinaria de calidad.'),
    'logo' => env('THEME_LOGO', ''),

    'colors' => [
        'primary' => env('THEME_COLOR_PRIMARY', '#103067'),
        'secondary' => env('THEME_COLOR_SECONDARY', '#c5a200'),
        'accent' => env('THEME_COLOR_ACCENT', '#ffc107'),
        'accent_light' => env('THEME_COLOR_ACCENT_LIGHT', '#ffda75'),
        'footer_bg' => env('THEME_FOOTER_BG', '#1a1a1a'),
        'sidebar_bg' => env('THEME_SIDEBAR_BG', '#343a40'),
        'sidebar_hover' => env('THEME_SIDEBAR_HOVER', '#495057'),
    ],

    'contact' => [
        'phone' => env('THEME_PHONE', '+51 1 7051923'),
        'email' => env('THEME_EMAIL', 'informes@equiposymaquinas.com'),
        'whatsapp' => env('THEME_WHATSAPP', '+51 949296155'),
        'address' => env('THEME_ADDRESS', 'Lima, Perú'),
    ],

    'social' => [
        'facebook' => env('THEME_FACEBOOK', 'https://facebook.com'),
        'twitter' => env('THEME_TWITTER', 'https://twitter.com'),
        'instagram' => env('THEME_INSTAGRAM', 'https://instagram.com'),
        'linkedin' => env('THEME_LINKEDIN', 'https://linkedin.com'),
    ],
];
