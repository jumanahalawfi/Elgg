<?php

return [
    'routes' => [
        'default:bin' => [
            'path' => '/bin',
            'resource' => 'bin/bin',
        ],
        'move:bin' => [
            'path' => '/bin/move',
            'resource' => 'bin/restoreMove',
        ]
    ],
    'events' => [
        'register' => [
            'menu:topbar' => [
                'Elgg\bin\Menus\Topbar::register' => [],
            ],
        ]
    ]
];