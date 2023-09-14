<?php

return [
    'TEMPLATE_PATH' => 'templates/',
    'CACHE_PATH' => 'var/cache/',
    'LOGS_PATH' => 'var/logs/',
    'prefix_ticket' => 'DTK',
    'db' => [
        'db_driver' => 'pgsql',
        'host' => 'localhost',
        'username' => 'rizqon',
        'password' => '123qweasd',
        'db_name' => 'detik',
    ],
    'rabbitmq' => [
        'host' => 'localhost',
        'port' => 5672,
        'username' => 'guest',
        'password' => 'guest',
        'num_worker' => 10,
    ],
];