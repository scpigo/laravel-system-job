<?php

return [
    'default' => 'sql',

    'schedulers' => [
        'sql' => [
            'queue_connection' => 'rabbitmq_jobs',
            'model' => 'Scpigo\SystemJob\Drivers\sql\Models\SystemJob',
        ],

        'mongo' => [
            'queue_connection' => 'rabbitmq_jobs',
            'model' => 'Scpigo\SystemJob\Drivers\mongo\Models\SystemJob',
        ]
    ]
];