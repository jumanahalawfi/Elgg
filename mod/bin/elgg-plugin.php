<?php

return [
    'plugin' => [
        'name' => 'Temporary Bin',
        'activate_on_install' => true,
    ],
	'routes' => [
		'default:bin' => [
			'path' => '/bin',
			'resource' => 'bin/bin',
		],
	],
	'events' => [
		'register' => [
			'menu:topbar' => [
				'Elgg\bin\Menus\Topbar::register' => [],
			],
		]
	]
];
