<?php

return [
    'path'      => base_path('modules'),

    'namespace' => 'Modules\\',

    'ignored'   => ['foundation'],

    'folders'   => [
        'config'        => 'config/',
        'migrations'    => 'database/migrations/',
        'assets'        => 'resources/assets/',
        'lang'          => 'resources/lang/',
        'views'         => 'resources/views/',
        'commands'      => 'src/Console/Commands',
        'controllers'   => 'src/Http/Controllers/',
        'middleware'    => 'src/Http/Middleware/',
        'requests'      => 'src/Http/Requests/',
        'providers'     => 'src/Providers/',
        'seeds'         => 'src/Seeds/',
        'start'         => 'start/',
        'tests'         => 'tests/',
    ],
];
