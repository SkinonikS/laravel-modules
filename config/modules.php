<?php

return [

    'namespace' => 'Modules',

    'path' => base_path('Modules'),

    // 'composer' => [
    //     'vendor' => 'nwidart',
    //     'author' => [
    //         'name' => 'Nicolas Widart',
    //         'email' => 'n.widart@gmail.com',
    //     ],
    //     'composer-output' => false,
    // ],

    'cache' => [
        'paths' => [
            // Only directory path. File names will be generated automatically.
            'manifests' => base_path('bootstrap/cache'),
            'module-services' => base_path('bootstrap/cache'),
        ],
    ],

    'activator' => 'file',

    'activators' => [
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/modules.php'),
        ],
    ],
];
