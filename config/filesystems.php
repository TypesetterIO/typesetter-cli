<?php

declare(strict_types=1);

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => getcwd(),
        ],
        'stubs' => [
            'driver' => 'local',
            'root' => __DIR__ . '/../stubs',
        ],
    ],
];
