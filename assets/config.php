<?php

return [
    'driver' => 'socket',
    'version' => 'v1.40',
    'drivers' => [
        'api' => [
            'url' => 'http://localhost:80',
        ],
        'socket' => [
            'unix_socket' => '/var/run/docker.sock',
        ],
    ],
    'extra_hosts' => [],
];
