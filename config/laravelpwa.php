<?php

return [
    'name' => 'Budget App',
    'manifest' => [
        'name' => env('APP_NAME', 'Budget App'),
        'short_name' => 'Budget App',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'green',
        'icons' => [
            '16x16' => [
                'path' => 'images/icons/favicon-16x16.png',
                'purpose' => 'any'
            ],
            '32x32' => [
                'path' => 'images/icons/favicon-32x32.png',
                'purpose' => 'any'
            ],
            '72x72' => [
                'path' => 'images/icons/72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => 'images/icons/96.png',
                'purpose' => 'any'
            ],
            '120x120' => [
                'path' => 'images/icons/120.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => 'images/icons/128.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => 'images/icons/android-chrome-192x192.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => 'images/icons/android-chrome-512x512.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => 'images/icons/apple-splash-640-1136.jpg',
            '750x1334' => 'images/icons/apple-splash-750-1334.jpg',
            '828x1792' => 'images/icons/apple-splash-828-1792.jpg',
            '1125x2436' => 'images/icons/apple-splash-1125-2436.jpg',
            '1242x2208' => 'images/icons/apple-splash-1242-2208.jpg',
            '1242x2688' => 'images/icons/apple-splash-1242-2688.jpg',
            '1536x2048' => 'images/icons/apple-splash-1536-2048.jpg',
            '1668x2224' => 'images/icons/apple-splash-1668-2224.jpg',
            '1668x2388' => 'images/icons/apple-splash-1668-2388.jpg',
            '2048x2732' => 'images/icons/apple-splash-2048-2732.jpg',

        ],
        'shortcuts' => [

            [
                'name' => 'IA Prompt',
                'description' => 'Generar con IA',
                'url' => '/budget/prompt',
            ],
            [
                'name' => 'Presupuestos',
                'description' => 'Listado presupuestos',
                'url' => '/budgets',
            ]
        ],
        'custom' => [
            'apple_touch_icon' => 'icons/apple-touch-icon.png',
            'favicon_32' => 'images/icons/favicon-32x32.png',
            'favicon_16' => 'images/icons/favicon-16x16.png',
        ],
    ]
];
