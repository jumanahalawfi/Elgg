<?php

return [
    'routes' => [
        'default:bin' => [
            'path' => '/bin',
            'resource' => 'bin',
        ],
    ],
    'events' => [
        'register' => [
            'menu:topbar' => [
                'Elgg\bin\Menus\Topbar::register' => [],
            ],
            'menu:topbar' => [
                'Elgg\Bin\Menus\Topbar::register' => [],
            ],
        ]
    ]
];